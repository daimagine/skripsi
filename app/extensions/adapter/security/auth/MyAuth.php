<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace app\extensions\adapter\security\auth;

use lithium\security\Auth;
use lithium\core\Libraries;
use li3_facebook\extensions\FacebookProxy;
use lithium\storage\Session;
use app\models\Users;
use \Exception;
use MongoId;

/**
 * Extends Lithium's Form auth adapter and adds a tiny little
 * extra step that will look for a Facebook session and use that
 * to set auth if available.
 *
 * @see lithium\security\auth\adapter\Form
*/

class MyAuth extends \lithium\security\auth\adapter\Form {


    //override hash string on Auth, it makes me confuse
    protected $_filters = array('password' => null);

	/**
	 * Called by the `Auth` class to run an authentication check against a model class using the
	 * credientials in a data container (a `Request` object), and returns an array of user
	 * information on success, or `false` on failure.
	 *
	 * @param object $credentials A data container which wraps the authentication credentials used
	 *               to query the model (usually a `Request` object). See the documentation for this
	 *               class for further details.
	 * @param array $options Additional configuration options. Not currently implemented in this
	 *              adapter.
	 * @return array Returns an array containing user information on success, or `false` on failure.
	 */
	public function check($credentials, array $options = array()) {
        //check if user login with their username
        $email = $credentials->data['email'];
        if(strpos($email, '@')==false){
            //then try to login with username
//            $this->setFields(array(
//                'username',
//                'password'
//            ));
//            $credentials->data['username'] = $email;
//            unset($credentials->data['email']);
//            $options['fields'] = array('username', 'password');
        }
//        var_dump($credentials->data);
        $user = parent::check($credentials, $options);
//        return false;
        // If the user didn't sign in using normal form method, try checking for a Facebook session
        if(!$user && isset($credentials->data['fbid'])) {
            //i guess we dont need to do some checking on fb session
            //cuz we already did it in controller
            //so, just searching for any matching user in database with fbid criteria
            // If $uid is set, then write the fb_logout_url session key
            $uid = $credentials->data['fbid'];
            if (!empty($uid)) {
                //instantiate session again
//                FacebookProxy::instanciateFacebookApi();
                Session::write('fb_logout_url', FacebookProxy::getLogoutUrl(array("next"=>"http://localhost/skripsi-dev/users/logout")));
                // Also, set Auth and return the user data
                $user_data = Users::handle_facebook_user($uid);
                if($user_data) {
                    $user = $user_data;
                } else {
                    //Auth::clear('minerva_user');
                }
            } else {
                Session::write('fb_login_url', FacebookProxy::getLoginUrl(array('req_perms'=>'email', "next"=>"http://localhost/skripsi-dev/users/login/fb/")));
            }
        }
        if($user != false) {
            //set last login time and ip
            $loging['last_login_time'] = new MongoId();
            $loging['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
            Users::update($loging, array('_id'=>$user['_id']));
        }
        return $user;
	}

    protected function setFields(array $fields = array()) {
        $this->_fields = $fields;
    }

}

?>