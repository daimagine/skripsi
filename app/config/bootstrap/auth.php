<?php

use lithium\storage\Session;
use lithium\security\Auth;
//use lithium\security\validation\RequestToken;

Session::config(array(
    'default' => array('adapter' => 'Php')
));

Auth::config(array(
     'web_user' => array(
         'adapter' => 'Form',
         'model' => 'Users',
         'fields' => array('email', 'password'),
         'scope'=>array('activated'=>true),
//         'session' => array(
//	    	'options' => array('name' => 'user_session')
//	    )
     )
));


?>