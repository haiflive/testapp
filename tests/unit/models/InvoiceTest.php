<?php

namespace tests\models;

use app\models\Invoice;
use app\models\User;
use Codeception\Specify;

/**
 *  composer exec codecept run tests/unit/models/BillingOperationsTest
 */

class InvoiceTest extends \Codeception\Test\Unit
{
    private $model;

    protected function _after()
    {
        \Yii::$app->user->logout();
    }
    
    public function testValidation()
    {
        $this->model = new Invoice([
            //
        ]);
        
        expect_not($this->model->save());
        expect($this->model->errors)->hasKey('owner_id');
        expect($this->model->errors)->hasKey('for_user_id');
        expect($this->model->errors)->hasKey('amount');
        expect($this->model->errors)->hasKey('login');
    }
    
    public function testNegativeAmount()
    {
        $this->model = new Invoice([
            'amount' => '-10',
        ]);

        expect_not($this->model->save());
        expect($this->model->errors)->hasKey('amount');
    }

    public function testBillingOperationsCorrect()
    {
        \Yii::$app->user->login(User::userRegistration('unit_test'), 30);
        
        $this->model = new Invoice([
            'login' => 'test',
            'amount' => '10',
        ]);
        
        $this->model->save();
        expect_that($this->model->save());
    }

}
