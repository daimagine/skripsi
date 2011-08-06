<?php

namespace app\extensions;

use lithium\storage\Session;

class SessionMessage {
    //TODO: Move process to a sessionmessage helper for a cleaner look.
    static function process(&$controller){
        if(Session::read('sessionMessage')){
            $messages = Session::read('sessionMessage');

            foreach($messages as $type => $message){
                switch($type){
                    case 'error':
                        $controller->set(array('errorMessage' => $message));
                        Session::delete("sessionMessage.error");
                        break;
                    case 'information':
                        $controller->set(array('informationMessage' => $message));
                        Session::delete("sessionMessage.information");
                        break;
                    case 'notice':
                        $controller->set(array('noticeMessage' => $message));
                        Session::delete("sessionMessage.notice");
                        break;
                }
            }
        }
    }

    static function setInfo($message = null){
        Session::write('sessionMessage.information', $message);
    }

    static function setNotice($message = null){
        Session::write('sessionMessage.notice', $message);
    }

    static function setError($message = null){
        Session::write('sessionMessage.error', $message);
    }
}

?>