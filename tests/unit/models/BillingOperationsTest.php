<?php

namespace tests\models;

use app\models\BillingOperations;
use app\models\User;
use Codeception\Specify;

/**
 *  composer exec codecept run tests/unit/models/BillingOperationsTest
 */

class BillingOperationsTest extends \Codeception\Test\Unit
{
    private $model;

    protected function _after()
    {
        \Yii::$app->user->logout();
    }
    
    public function testValidation()
    {
        $this->model = new BillingOperations([
            //
        ]);

        expect_not($this->model->save());
        expect($this->model->errors)->hasKey('login');
        expect($this->model->errors)->hasKey('user_id');
        expect($this->model->errors)->hasKey('reciver_id');
        expect($this->model->errors)->hasKey('amount');
    }
    
    public function testNegativeAmount()
    {
        $this->model = new BillingOperations([
            'amount' => '-10',
        ]);

        expect_not($this->model->save());
        expect($this->model->errors)->hasKey('amount');
    }

    public function testBillingOperationsCorrect()
    {
        \Yii::$app->user->login(User::userRegistration('unit_test'), 30);
        
        $this->model = new BillingOperations([
            'login' => 'test',
            'amount' => '10',
        ]);
        
        $this->model->save();
        expect_that($this->model->save());
    }

}
