<?php

namespace app\tests\cases\models;

use app\models\Users;

use \lithium\security\Auth;
use \lithium\storage\Session;

class UsersTest extends \lithium\test\Unit {

	 public function setUp() {
        Session::config(array(
            'test' => array('adapter' => 'Cookie')
        ));

        Auth::config(array(
            'test' => array(
                'adapter' => '\lithium\tests\mocks\security\auth\adapter\MockAuthAdapter',
                'models' => 'Users',
                'fields' => array('email', 'password')
            )
        ));
    }

    public function testAuthLoginLogout() {
//        $webUser = Users::create();
//        $webUser->email = 'qlicquerz_boyz@yahoo.co.id';
//        $webUser->password = '4zz4hr4';
//        $user = array('user' => $webUser);
//
//        $result = Auth::check('test', $user, array('success' => true));
//        $this->assertEqual($user, $result);
//
//        $result = Auth::check('test');
//        $this->assertEqual($user, $result);
//
//        Auth::clear('test');
//        $this->assertFalse(Auth::check('test'));
    }

    public function testUpdate() {
        $data = array('name' => 'rest');
        $conditions = array('_id' => 'rest');
        $result = Users::update($data, $conditions, array('safe' => true));
        $this->assertFalse($result);
    }

}

?>