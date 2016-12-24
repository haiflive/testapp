<?php

namespace tests\models;

use app\models\LoginForm;
use Codeception\Specify;

/**
 *  composer exec codecept run tests/unit/models/LoginFormTest
 */

class LoginFormTest extends \Codeception\Test\Unit
{
    private $model;

    protected function _after()
    {
        \Yii::$app->user->logout();
    }

    public function testLoginNoUser()
    {
        $this->model = new LoginForm([
            'username' => 'not_existing_username',
            // 'password' => 'not_existing_password', //! no password
        ]);

        expect_that($this->model->login());
        expect_not(\Yii::$app->user->isGuest);
    }

    public function testLoginCorrect()
    {
        $this->model = new LoginForm([
            'username' => 'demo',
        ]);

        expect_that($this->model->login());
        expect_not(\Yii::$app->user->isGuest);
        expect($this->model->errors)->hasntKey('password');
    }

}
