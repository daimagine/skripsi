<?php
/**
 * User: dai
 * Date: 7/16/11
 * Time: 11:22 AM
 */
use lithium\core\Libraries;

LIbraries::add('li3_swiftmailer');

use li3_swiftmailer\mailer\Transports;

Transports::config(array('default' => array(
    'adapter' => 'Smtp',
    'host' => 'smtp.google.com',
//    'port' => 587,
    'port' => 25,
//    'port' => 465,
    'encryption' => 'tls',
    'username' => 'qlicquerzboyz',
    'password' => 'fauziahazzahra'
)));



?>