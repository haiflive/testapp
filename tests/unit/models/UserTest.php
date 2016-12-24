<?php
namespace tests\models;
use app\models\User;

class UserTest extends \Codeception\Test\Unit
{
    
    public function testUserRegistration()
    {
        expect_that($user = User::userRegistration('admin'));
    }
    
    public function testFindUserById()
    {
        expect_that($user = User::findIdentity(1));
        expect($user->login)->equals('admin');

        expect_not(User::findIdentity(999));
    }
    
    public function testFindUserByUsername()
    {
        expect_that($user = User::findByUsername('admin'));
        expect_not(User::findByUsername('not-admin'));
    }

    /**
     * @depends testFindUserByUsername
     */
    public function testValidateUser($user)
    {
        $user = User::findByUsername('admin');

        expect_that($user->validatePassword('admin'));
        expect_that($user->validatePassword('123456'));
        expect_that($user->validatePassword('123456'));
    }

}
