<?php
/**
 *  composer exec codecept run tests/functional/LoginFormCest
 */

class RegistrationCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('user/create');
    }

    public function openLoginPage(\FunctionalTester $I)
    {
        $I->see('Registration', 'h1');
    }
    
    public function registerAsAdmin(\FunctionalTester $I) {
        \app\models\User::userRegistration('admin'); // preregistration if not exists
        
        $I->submitForm('#registration-form', [
            'User[login]' => 'admin',
        ]);
        
        $I->expectTo('see validations errors');
        $I->see('has already been taken');
    }
}