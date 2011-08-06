<?php

namespace app\models;

use \lithium\util\Validator;
use app\extensions\Util;
use \Exception;
use \li3_facebook\extensions\FacebookProxy;

use MongoDate;
use MongoId;
use \lithium\data\collection\DocumentArray;

use lithium\security\Auth;

class Messages extends \lithium\data\Model {

	public $validates = array();
    protected $_meta = array('source' => 'messages');


    public static function addMessage($data) {
        //i guess i have to make two instance of messages
        //ones for the web user and ones for user being sent a message
        //each message for first addition will given an id of user as owner
        //
        //first, create new document contains owner field if not exist
        $messages = Messages::find('first', array('conditions' => array('owner'=>$data['user_id'])));
        if($messages == null){
            $messages = Messages::create(array('owner' => $data['user_id']));
            $messages->conversation = new DocumentArray();
            $messages->conversation->append(array(
                    'friend_id' => $data['friend_id'],
                ));
            $messages->save();
        } else {
            $query = array(
                '$addToSet' => array(
                    'conversation' => array(
                        'friend_id' => $data['friend_id']
                    )
                )
            );
            $conditions = array('owner' => $data['user_id'], 'conversation.friend_id' => array('$ne' => $data['friend_id']));
            Messages::update($query, $conditions, array('atomic'=>false));
        }
        $secondmessages = Messages::find('first', array('conditions' => array('owner'=>$data['friend_id'])));
        if($secondmessages == null){
            $secondmessages = Messages::create(array('owner' => $data['friend_id']));
            $secondmessages->conversation = new DocumentArray();
            $secondmessages->conversation->append(array(
                    'friend_id' => $data['user_id'],
                ));
            $secondmessages->save();
        } else {
             $query = array(
                '$addToSet' => array(
                    'conversation' => array(
                        'friend_id' => $data['user_id']
                    )
                )
            );
            $conditions = array('owner' => $data['friend_id'], 'conversation.friend_id' => array('$ne' => $data['user_id']));
            Messages::update($query, $conditions, array('atomic'=>false));
        }
//        print "<pre>".print_r($messages, true)."</pre>";

        //then append new conversation to it
        //to the first user message document
        $query = array(
            '$push' => array(
                'conversation.$.messages' => array(
                    'id' => new MongoId(),
                    'from' => $data['user_id'],
                    'content' => $data['message'],
                    'time' => new MongoDate(),
                ),
            )
        );
        $conditions = array('owner' => $data['user_id'], 'conversation.friend_id' => $data['friend_id']);
        Messages::update($query, $conditions, array('atomic'=>false));

        //then the second user or friend being sent a message document
        $query['$push']['conversation.$.messages']['id'] = new MongoId();
        $conditions = array('owner' => $data['friend_id'], 'conversation.friend_id' => $data['user_id']);
        Messages::update($query, $conditions, array('atomic'=>false));

//        print "<pre>".print_r($conditions, true)."</pre>";
//        print "<pre>".print_r($query, true)."</pre>";
    }

    public static function removeMessage($messageId){
//        $query = array(
//            '$pull' => array('conversation.messages')
//        );
        $conditions = array(
            'conversation' => array (
                '$elemMatch' => array (
                    'messages' => array (
                        '$elemMatch' =>array (
                            'id' => new MongoId($messageId),
                        ),
                    ),
                ),
            ),
        );
        print "<pre>".print_r(json_encode($conditions), true)."</pre>";
        $messages = Messages::find('first', $conditions);
        $conversation = $messages->conversation;
        print $conversation->count();
        print "<pre>".print_r($messages->data(), true)."</pre>";
        foreach($conversation as $idx => $messageColl) {
//            print "1<pre>".print_r($messageColl->data(), true)."</pre>";
            foreach($messageColl->messages as $msgIdx => $messageObj) {
                if($messageObj->id == $messageId) {
//                    var_dump($messageObj);
                    break;
                }
            }
        }
//        return Messages::update($query, $conditions );
    }

    /**
     * @static
     * @param array $data
     *      '_id'           : messages document id
     *      'messageId'     : id of message array in conversation embed doc
     * @return void
     */
    public static function deleteMessage($data = array()) {
        if(empty($data))
            return false;
        foreach($data as $key => $value) {
            $data[$key] = ($value instanceof MongoId)?: new MongoId($value);
        }
        $criteria = array(
            'conditions' => array(
                '_id' => $data['_id'],
            )
        );
//        print "<pre>".print_r($data,true)."</pre>";
        $messages = Messages::find('first', $criteria);
//        print "<pre>".print_r($messages->data(), true)."</pre>";
        $conversation = $messages->conversation;
//        print $conversation->count();
//        print "<pre>".print_r($conversation->data(), true)."</pre>";
        $no = 0;
        $flag = false;
//        foreach($conversation as $idx => $messageColl) {
        for($idx=0; $idx<$conversation->count(); $idx++) {
            $messageColl = $conversation[$idx];
            // print "$idx<pre>".print_r($messageColl->data(), true)."</pre>";
            foreach($messageColl->messages as $msgIdx => $messageObj) {
                if($messageObj->id == $data['messageId']) {
                    //if just left one message then remove the conversation
                    if($messageColl->messages->count()==1) {
                        //                die('sip');
                        $flag = true;
//                        print "<pre>".print_r($conversation->data(), true)."</pre>";
                        //                        unset($conversation[$idx]);
                        static::removeConversation($data['_id'], $idx);
//                        print "<br><br>After<pre>".print_r($conversation->data(), true)."</pre>";
//                        return 'empty';
//                        print "<pre>".print_r($messageColl->friend_id, true)."</pre>";
//                        die();
//                        unset($conversation[$idx]);
                    }
                    //                    print "$no<pre>".print_r($messageObj->data(), true)."</pre>";
                    //                    print "$no<pre>".print_r($messageColl->messages[$msgIdx], true)."</pre>";
                    //                    unset($messageColl->messages[$msgIdx]);
                    unset($conversation[$idx]->messages[$msgIdx]);
                    //                    $conversation[$idx] = $messageColl;
                    //                    print $idx;
                    //
                    break;
                }
            }
        }
        $messages->conversation = $conversation;
        if($flag)
            return 'empty';
        return $messages->save();
//        print "<pre>".print_r($conversation, true)."</pre>";
//        }
//        print "<pre>".print_r($conversation->count(), true)."</pre>";
    }

    private static function removeConversation($owner, $idx) {
        $query = array(
            '$unset' => array(
                "conversation.$idx" => true
            )
        );
//        $conditions = array(
//            '$pull' => array (
//                'conversation' => array (
//                    '$elemMatch' => array (
//                        'friend_id' => $idx
//                    )
//                )
//            )
//        );
//        $conditions = array('owner' => $owner);
//        print "<pre>".print_r($query, true)."</pre>";
        Messages::update($query);
        //then remove the null shit
        $query = array(
            '$pull' => array( 'conversation' => null )
        );
        Messages::update($query);
    }

}

?>