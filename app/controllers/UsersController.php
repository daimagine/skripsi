<?php

namespace app\controllers;

use app\models\Users;
use app\models\Messages;

use \li3_facebook\extensions\FacebookProxy;
use \li3_facebook\extensions\helper\Facebook;

use lithium\security\Auth;
use lithium\storage\Session;
use \li3_flash_message\extensions\storage\FlashMessage;

use MongoId;
use MongoDate;
use \lithium\data\collection\DocumentArray;
use \lithium\data\collection\DocumentSet;

use \lithium\util\Validator;
use lithium\core\Libraries;
//use app\extensions\Facebook;

use \lithium\template\View;
use \Swift_MailTransport;
use \Swift_SmtpTransport;
use \Swift_Mailer;
use \Swift_Message;
use \Swift_Attachment;

class UsersController extends \lithium\action\Controller {

	public function index() {
        //lets retrieve session user
        $web_user = Auth::check('web_user');
		$users = Users::all();
        $title = 'List Users';
		return compact('users', 'title', 'web_user');
	}

	public function view() {
        //lets retrieve session user
        $web_user = Auth::check('web_user');
        //check it then
//        if(!$web_user)
//            return $this->redirect('Users::index');
        //redirect to dashboard if its self
        if((string)$web_user['_id'] == (string)$this->request->id)
            return $this->redirect('Users::dashboard');
        //get user want to viewed by
		$user = Users::first($this->request->id);
//        var_dump($this->request->id);
//        var_dump($user);
        $this->_render['template'] = 'dashboard';
		return compact('user', 'web_user');
	}

	public function add() {
        //lets retrieve session user
        $web_user = Auth::check('web_user');
		$user = Users::create();

		if (($this->request->data) && $user->save($this->request->data)) {
			$this->redirect(array('Users::view', 'args' => array($user->id)));
		}
		return compact('user', 'web_user');
	}

	public function edit() {
        //lets retrieve session user
        $web_user = Auth::check('web_user');
		$user = Users::find($this->request->id);

		if (!$user) {
			$this->redirect('Users::index');
		}
		if (($this->request->data) && $user->save($this->request->data)) {
			$this->redirect(array('Users::view', 'args' => array($user->id)));
		}
        $this->_render['layout'] = 'blank';
		return compact('user', 'web_user');
	}

    public function editProfile(){
        $web_user = Auth::check('web_user');
		$user = Users::find($web_user['_id']);
        $success = false;
		if (($this->request->data) && $user->save($this->request->data)) {
			return $this->render(array('json'=>array('success'=>true)));
		}
        $this->_render['layout'] = 'blank';
        $this->_render['template'] = 'edit';
		return compact('user', 'web_user');
    }

	public function delete() {
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Users::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		Users::find($this->request->id)->delete();
		$this->redirect('Users::index');
	}

    public function signup($scenario='rad'){
        //lets retrieve session user
        $web_user = Auth::check('web_user');
        //get facebook session
        $facebook = FacebookProxy::instanciateFacebookApi();
        //create new user
        $user = Users::create();
        //set validator
        $rules = array(
            'email' => array(
                array('notEmpty', 'message' => 'E-mail cannot be empty.'),
                array('email', 'message' => 'E-mail is not valid.'),
                array('uniqueEmail', 'message' => 'Sorry, this e-mail address is already registered.'),
            ),
            'username' => array(
                array('notEmpty', 'message' => 'Username cannot be empty.'),
                array('alphaNumeric', 'message' => 'Username must be alpha numeric only'),
                array('uniqueUsername', 'message' => 'Sorry, this username is already taken.'),
                array('moreThanFive', 'message' => 'Username must be at least 6 characters long.')
            ),
            'password' => array(
                array('notEmpty', 'message' => 'Password cannot be empty.'),
                array('alphaNumeric', 'message' => 'Password must be alpha numeric only'),
                array('notEmptyHash', 'message' => 'Password cannot be empty.'),
                array('moreThanFive', 'message' => 'Password must be at least 6 characters long.')
            )
        );
        //set another rules if connect with facebook so the fbid is unique
        if($scenario=='fb'){
            $rules += array(
                'fbid' => array(
                    array('uniqueFbid', 'message' => 'Sorry, this facebook account is already registered.'),
                )
            );
        }
        //if user has submitted the signup form then execute this routing
        if($this->request->data){
            if($user->save($this->request->data, array('validate' => $rules))) {
                //routine to send activation email
                // TODO : Activation email
                FlashMessage::write('User registration successful. You may sign in now.',
                       array('type'=>'success', 'sticky'=>true), 'web_user');
                $this->redirect('Users::login');
            } else {
                FlashMessage::write('Registration failed', array('type'=>'warning'), 'web_user');
                $this->request->data['password'] = '';
            }
        } else {
            //check if user using login fb button. if thereis no post data then
            //just fetching user data from facebook api such as username and email.
            //then redirect it. and so on
            if($scenario=='fb') {
                $signUpFb = true;
                $session = FacebookProxy::getSession();
                $uid = null;
                // Session based API call.
                if ($session) {
                    // Set the session
    //                    Session::write('fb_session', $session);
                    try {
                        $uid = FacebookProxy::getUser();
                        $fbUser = FacebookProxy::api('/me');
                        //then set email and username
                        $user->name = $fbUser['name'];
//                        $user->username = $fbUser['username'];
                        $user->email = $fbUser['email'];
                        $user->fbid = $uid;
                    } catch (Exception $e) {
                        error_log($e);
                    }
                }
            }
        }
        $title = 'SignUp';
        $subHeader = 'Sign Up';
        return compact('user', 'facebook', 'title', 'subHeader', 'scenario', 'web_user');
    }

    public function logout(){
        Auth::clear('web_user');
        Session::delete('fb_logout_url');
        return $this->redirect('/');
    }

    public function login($scenario='rad'){
        //lets retrieve session user
        $web_user = Auth::check('web_user');
        $this->set(compact('web_user'));
        //if already logged in
        if(Auth::check('web_user'))
            return $this->redirect('/');
        //get facebook session
//        FacebookProxy::reset();
//        FacebookProxy::config(Libraries::get('li3_facebook'));
        $facebook = FacebookProxy::instanciateFacebookApi();
//        $facebook = FacebookProxy::getApiInstance();
//        var_dump($facebook);
        //facebook connect
        if($scenario=='fb'){
            $signUpFb = true;
            $session = FacebookProxy::getSession();
//            var_dump($session);
            $uid = null;
            // Session based API call.
            if ($session) {
                // Set the session
//                    Session::write('fb_session', $session);
                try {
                    $credentials = array();
                    $uid = FacebookProxy::getUser();
//                    $fbUser = FacebookProxy::api('/me');
                    //then set email and username
//                    $user->name = $fbUser['name'];
//                        $user->username = $fbUser['username'];
//                    $credentials['email'] = $fbUser['email'];
                    $credentials['fbid'] = $uid;
                    $this->request->data = $credentials;
                    $result = Auth::check('web_user', $this->request);
                    if($result!=false){
                        return $this->redirect('/users/dashboard');
                    }
//                    var_dump($result);echo "<hr>";
//                    var_dump($credentials);echo "<hr>";
//                    var_dump(Session::read('web_user'));echo "<hr>";
//                    var_dump(Auth::check('web_user'));echo "<hr>";
                } catch (Exception $e) {
                    error_log($e);
                    FlashMessage::write("Facebook connect is not available.");
                }
            }
//            else{die('session');}
        }
        //set rules
        $rules = array(
            'email' => array(
                array('notEmpty', 'message' => 'Please insert your registered email'),
            ),
            'password' => array(
                array('notEmpty', 'message' => 'Password cannot be empty.'),
            )
        );
//        Auth::clear('web_user');
//        var_dump($this->request->data);
        if(!empty($this->request->data)){
            $error = Validator::check($this->request->data, $rules);
            if(!empty($error)){
//                FlashMessage::write($error);
            } else if($result = Auth::check('web_user', $this->request)){
                return $this->redirect('/users/dashboard');
//                var_dump(Session::read('user'));
            } else {
//            var_dump($result);
    //        $user = Users::find('first', array('email'=>'qlicquerz_boyz@yahoo.co.id'));
    //        Session::write('user',$user);
    //            var_dump(Session::read('user'));
    //        Session::delete('user');
                FlashMessage::write("Wrong email or password.");
            }
        }
        //create user object
//        $user = Users::create();
        $user = null;
        $title = 'Login';
//        $subHeader = 'Sign Up';
        return compact('user', 'facebook', 'title', 'scenario');
    }

    public function dashboard(){
        //lets retrieve session user
        $web_user = Auth::check('web_user');
//        var_dump($web_user);
        //check it then
        if(!$web_user)
            return $this->redirect('Users::index');
        //get user data
//        $user = Users::first(array('conditions' => array('_id' => $web_user['_id'])));
        $user = Users::find('first', array(
            'conditions' => array('_id' => $web_user['_id'])
        ));
//        var_dump($user);
        //instantiate facebook again
        $facebook =  FacebookProxy::instanciateFacebookApi();
        if($user->password==null) {
            FlashMessage::write('You have not set any password yet. Password is crucial for sign in with your email. Please use Change Password link to set your password. If you already had, then ignore this message.',
                   array('type'=>'warn', 'sticky'=>true), 'web_user');
        }
        //then get another data that linked to this user
        return compact('user', 'facebook', 'web_user');
    }

    public function changePassword(){
        $result = 'failed';
        $data = $this->request->data;
        if(!empty($data)){
            $result = Users::update(
                array('password' => $data['password']), //data being updated
                array('_id' => $data['_id']) //conditions
                //options
            );
            $result = ($result)? 'success' : 'failed';
        }
        if($this->request->is('ajax'))
            $this->render(array('json' => (object) compact('result')));
    }

    public function addFriend() {
        $result = false;
        $web_user = Auth::check('web_user');
        if(!$web_user) {
            return $this->redirect('/users/login');
        } else {
            if(empty($this->request->id)) {
                return $this->redirect('/users/index');
            } else {
                $result = Users::addFriend($web_user['_id'], $this->request->id);
            }
        }
        if($this->request->is('ajax'))
            $this->render(array('json' => (object) compact('result')));
        return $this->redirect('/users/view/'.$this->request->id);
    }


    public function approveRequest(){
        $result = false;
        $web_user = Auth::check('web_user');
        if(!$web_user) {
            return $this->redirect('/users/login');
        } else {
            if(empty($this->request->id)) {
                return $this->redirect('/users/index');
            } else {
                $result = Users::approveRequest($web_user['_id'], $this->request->id);
//                die();
            }
        }
        if($this->request->is('ajax'))
            $this->render(array('json' => (object) compact('result')));
        return $this->redirect('/users/dashboard');
    }

    public function sendMessage(){
        $result = false;
//        $user = null;
        $web_user = Auth::check('web_user');
        if(!$web_user && !isset($this->request->id)) {
            return $this->redirect('/users/login');
        } else {
            $user = Users::first($this->request->id);
            if($this->request->data) {
                print "<pre>".print_r($this->request->data, true)."</pre>";
                $web_user_id = new MongoId($web_user['_id']);
                $friend_id = $user->_id;
//                var_dump($user->_id);
//                var_dump($web_user_id);

                $data = array(
                    'user_id' => $web_user_id,
                    'friend_id' => $friend_id,
                    'message' => $this->request->data['message']
                );
                Messages::addMessage($data);
//                var_dump($this->request->data);
//                die();
                if($this->request->data['callback'])
                    return $this->redirect($this->request->data['callback']);
                return $this->redirect("/users/messages");
            }
        }
        return compact('user', 'web_user');
    }

    public function messages(){
        $web_user = Auth::check('web_user');
        if(!$web_user)
            return $this->redirect('/users/login');
        $messages = Messages::find('first', array(
            'conditions' => array('owner' => new MongoId($web_user['_id']))
        ));

        $user = Users::find('first', array(
            'conditions' => array('_id' => $web_user['_id'])
        ));
//        var_dump($user);
        //instantiate facebook again
        $facebook =  FacebookProxy::instanciateFacebookApi();
        //then get another data that linked to this user

        return compact('user', 'facebook', 'messages', 'web_user');
    }

    public function messageDetail(){
        $web_user = Auth::check('web_user');
        if(!$web_user)
            return $this->redirect('/users/login');
        $messages = Messages::find('first', array(
            'conditions' => array(
                'owner' => new MongoId($web_user['_id'])
            ),
            'fields' => array()
        ));

        $user = Users::find('first', array(
            'conditions' => array('_id' => $web_user['_id'])
        ));
//        var_dump($user);
        //instantiate facebook again
        $facebook =  FacebookProxy::instanciateFacebookApi();
        //then get another data that linked to this user
        $detail = $this->request->id;
        $this->_render['template'] = 'messages';
        return compact('user', 'facebook', 'messages', 'detail', 'web_user');
    }

    public function removeMessage($id, $messageId, $friendId) {
        $result = false;
        $web_user = Auth::check('web_user');
        if(!$web_user)
            return $this->redirect('/users/login');
        if(!isset($messageId))
            return $this->redirect('/users/messages');

//        var_dump($id, $messageId, $friendId);
//        $result = Messages::removeMessage($messageId);
        $result = Messages::deleteMessage(array('_id'=>$id, 'messageId'=>$messageId));
//        die();
        if($result=='empty') {
            return $this->redirect("/users/messages");
        }
        if($this->request->is('ajax'))
            $this->render(array('json' => compact('result')));
        return $this->redirect("/users/messageDetail/$friendId");
    }

    public function activateAccount($token=null) {
        if($token==null) {
            FlashMessage::write('Activation failed due to expired or bad token. Please signup once again.',
                array('type'=>'notice', 'sticky'=>true), 'web_user');
            return $this->redirect('/');
        }
        
    }

}

?>
