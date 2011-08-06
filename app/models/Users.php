<?php

namespace app\models;

use \lithium\util\Validator;
use MongoId;
use MongoDate;
use app\extensions\Util;
use \li3_facebook\extensions\FacebookProxy;
use \Exception;

use lithium\security\Auth;

class Users extends \lithium\data\Model {
    
    public static $friendRequestApproved = 45;
    public static $friendRequestUnApproved = 46;

    var $friendRequestStatus;

	protected $_meta = array('source' => 'users');

    /**
     * @var array of validation rules
     * used to define attribute rules. for example 'title' => array('notEmpty', 'message' => 'You need a title'),
     * @see lithium\data\Model
     */
    public $validates = array();

    public static function __init(){
        /*
		 * Some special validation rules
		*/
		Validator::add('uniqueEmail', function($value) {
			$user = Users::find('first', array('fields' => array('_id'), 'conditions' => array('email' => $value)));
			if(!empty($user)) {
			    return false;
			}
			return true;
		});

		Validator::add('notEmptyHash', function($value) {
			if($value == 'da39a3ee5e6b4b0d3255bfef95601890afd80709') {
			    return false;
			}
			return true;
		});

		Validator::add('moreThanFive', function($value) {
			if(strlen($value) < 5) {
			    return false;
			}
			return true;
		});

        Validator::add('uniqueFbid', function($value) {
            $user = Users::find('first', array('fields' => array('_id'), 'conditions' => array('fbid' => $value)));
			if(!empty($user)) {
			    return false;
			}
			return true;
        });

        Validator::add('uniqueUsername', function($value) {
            $user = Users::find('first', array('fields' => array('_id'), 'conditions' => array('username' => $value)));
			if(!empty($user)) {
			    return false;
			}
			return true;
        });
        
        parent::__init();
    }

    public function save($entity, $data = null, array $options = array()) {
        //assign data to entity
        if($data) {
            $entity->set($data);
        }

        //if new record
        if(!$entity->_id) {
            //set created
            $entity->created = new MongoDate();
            //hash the password
//            $entity->password = \lithium\util\String::hash($entity->password);
            //if connect using facebook then just activate without email activation
            if($entity->fbid){
                $entity->confirmed = true;
                $entity->activated = true;
            }else{
                //for new registered user, set the confirmed to false
                $entity->confirmed = false;
                //and activated to false also
                $entity->activated = false;
                //and create some token for email activation
                $url  = 'http://localhost/skripsi-dev/users/activateAccount/';
                $url .= Util::unique_string();
    //            var_dump($url);
                $entity->activationUrl = $url;
            }
            //just bypass until i find out how to send activation email
            //TODO : make activation email then delete these two lines below
            $entity->confirmed = true;
            $entity->activated = true;
        }
        //set updated
        $entity->updated = new MongoDate();
        //go to the next chain
        return parent::save($entity, null, $options);
    }

    /**
	 * Handles a Facebook user.
	 * If the user does not yet exist in the local database, they will be added.
	 * However, Facebook doesn't allow us to store any personal information about the user.
	 * So we're just going to store their Facebook uid and also a created, modified date, etc.
	 * Then for existing users, we'll update the last login time and IP.
	 *
	 * @param $facebook_uid String This will be the user's uid passed from the Facebook API
	*/
	public static function handle_facebook_user($facebook_uid=null) {
		if(empty($facebook_uid)) {
			return false;
		}

		$me = null;
		try {
			$me = FacebookProxy::api('/me');
		} catch(Exception $e) {
			error_log($e);
		}

		if(empty($me)) {
			return false;
		}

//		$now = date('Y-m-d h:i:s');

        // If logged in via Facebook Connect, see if the user exists in the local DB, if not, save it.
        $user = Users::find('first', array('conditions' => array('fbid' => $me['id'])));
		$user_data = false;

		if(!$user) {
			// Save the new user
			$user_document = Users::create();
			$user_data = array(
				'fbid' => $me['id'],
                'username' => $me['username'],
                'name' => $me['name'],
				'confirmed' => true,
				'activated' => true,
//				'url' => Util::unique_url(array('url' => 'fb-user', 'model' => 'minerva\models\User')),
//				'created' => $now,
//				'modified' => $now,
				'email' => $me['email'],
                'password' => null,
//                'role' => 'registered_facebook_user',
                'profile_pics' => array('primary' => true, 'url' => 'http://graph.facebook.com/'.$facebook_uid.'/picture?type=square')
//                'last_login_time' => new MongoDate(),
//                'last_login_ip' => $_SERVER['REMOTE_ADDR'],
			);
			$user_document->save($user_data, array('validate' => false));
            $user_data['_id'] = $user_document['_id'];
		} else {
			$user_data = $user->data();
		}
		return $user_data;

	}

    public function getName($record) {
//        return "{$record->name}";
        return ($record->name)? $record->name : $record->username;
    }

    public function isSelf($record) {
        if($web_user = Auth::check('web_user')){
            if($web_user['_id']==$record->_id)
                return true;
        }
        return false;
    }

    /**
     * Check if user with fid is friends of user given id
     * @static
     * @param  $id User id
     * @param  $fid Friend id
     * @return boolean True if fid is a friend of id
     */
    public static function checkFriendship($id, $fid) {
        $id = ($id instanceof MongoId)?: new MongoId($id);
        //$result = Users::first($id);
        $conditions = array(
            '_id' => $id,
            'friendlist.friend_id' => $fid,
            'friendlist.approved' => true
        );
        $result = Users::find('first', $conditions);
        if($result)
            return true;
        return false;
    }

    public function isFriend($record, $fid) {
//        $fid = (string) $fid;
//        var_dump($fid);
        $friendlist = $record->friendlist;
        if($friendlist) {
            $friends = $friendlist->data();
            foreach($friends as $friend) {
//                print "<pre>".print_r($friend,true)."</pre>";
//                break;
//                var_dump('fid : '.$fid);var_dump('  friend_id : '.$friend['friend_id']);
                if((string)$friend['friend_id'] == (string)$fid) {
                    $result['isRequester'] = $friend['isRequester'];
                    if($friend['approved']) {
                        $result['code'] = static::$friendRequestApproved;
                        return $result;
                    } elseif($friend['approved'] == false) {
                        $result['code'] = static::$friendRequestUnApproved;
                        return $result;
                    }
                    // print ($friend['friend_id'] == $fid)?'ya':'nope';
                }
//                print "<pre>".print_r($friend,true)."</pre>";
            }
            //            print "<pre>".print_r($friends,true)."</pre>";
        }
//        var_dump(compact('friendlist'));
        return false;
    }

    public function getFriends($record) {
        $amigos = array();
        foreach ($record->friendlist as $friends) {
            if($friends['approved'] == true){
                $amigos[] = $friends;
            }
//            var_dump($friends);
        }
        return $amigos;
    }

    public function getPendingRequests($record) {
        $amigosNotYet = array();
        foreach($record->friendlist as $friends) {
            if($friends['approved'] == false && $friends['isRequester'] == true) {
                $amigosNotYet[] = $friends;
            }
        }
        return $amigosNotYet;
    }

    public function getFriendRequests($record) {
        $amigosNopeNope = array();
        foreach($record->friendlist as $friends) {
            if($friends['approved'] == false && $friends['isRequester'] == false) {
                $amigosNopeNope[] = $friends;
            }
        }
        return $amigosNopeNope;
    }

    //TODO : dont forget to decrementing friendRequests, and incrementing friendsCount so as update approved to true
    //TODO : i think we should update time to current MongoDate also
    public static function approveRequest($id = null, $fid = null) {
        if($id == null || $fid == null)
            return false;
        $id = new MongoId($id);
        $fid = new MongoId($fid);
        $result = false;
        $query = array(
            '$set' => array(
//                'friendlist' => array(
//                    'time' => new MongoDate(),
//                    'approved' => true
//                ),
                'friendlist.$.time' => new MongoDate(),
                'friendlist.$.approved' => true
            ),
            '$inc' => array(
                'friendRequests' => -1,
                'friendsCount' => 1,
            ),
        );
        $conditions = array(
            '_id' => $id,
            'friendlist.friend_id' => $fid,
        );
        $result = Users::update($query, $conditions, array('atomic' => false));
        //set the other one
        $conditions = array(
            '_id' => $fid,
            'friendlist.friend_id' => $id,
        );
        $result = Users::update($query, $conditions, array('atomic' => false));
        var_dump($conditions);
        return $result;
    }

    /**
     * @query db.users.update({"username":"daimagine", "friendlist.friend_id":{"$ne":"dfd"}}, {"$addToSet":{"friendlist":{"friend_id":"dfd"}}, "$inc":{"friendsCount":1}});
     * @param null $id
     * @param null $fid
     * @return bool
     */
    public static function addFriend($id = null, $fid = null){
        if($id == null || $fid == null)
            return false;
        //set to mongo id
        $id = new MongoId($id);
        $fid = new MongoId($fid);
        //then addToSet this new friend on first user
        $query = array(
            '$addToSet' => array(
                'friendlist' => array(
                    'friend_id' => $fid,
//                    'time' => new MongoDate(),
                    'approved' => false,
                    'isRequester' => true
                ),
            ),
            //'$inc' => array('friendRequests' => 1) //increments the total friend requests
        );
        $conditions = array(
            '_id' => $id,
            'friendlist.friend_id' => array('$ne'=> $fid),
        );
        Users::update($query, $conditions, array('atomic' => false));

        //and another silly thing is, i cant find a way than this.
        //have to use two db operations to push friend id on each user.
        //you know dude, its just have to reverse between friend_id and _id.
        //friends and friends, crap...
        $query = array(
            '$addToSet' => array(
                'friendlist' => array(
                    'friend_id' => $id,
//                    'time' => new MongoDate(),
                    'approved' => false,
                    'isRequester' => false
                ),
            ),
            '$inc' => array('friendRequests' => 1) //increments the total friend requests
        );
        $conditions = array(
            '_id' => $fid,
            'friendlist.friend_id' => array('$ne'=> $id),
        );
        $result = Users::update($query, $conditions, array('atomic' => false));

        //because of we're use NOT SAFE mongodb query, it always return true.
        return $result;
    }


    public static function getUserById($id = null) {
        $anonim = 'Anonymous';
        if($id == null)
            return $anonim;
        $conditions = array('_id' => $id);
//        $fieldget = array('name' => true);
        $user = Users::find('first', array('conditions' => $conditions));
//        var_dump($user);
        if($user)
            return $user;
        return $anonim;
    }

    public function getProfilePicture($record) {
        if (!isset($record->profile_pics))
            return false;
        //TODO return stored profile picture uploaded by user
    }

}

?>