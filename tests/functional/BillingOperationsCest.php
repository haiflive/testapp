<?php
/**
 *  composer exec codecept run tests/functional/LoginFormCest
 */

class BillingOperationsCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('/');
    }
    
    public function openMainPage(\FunctionalTester $I) {
        
        $I->amLoggedInAs('unit_test');
        $I->amOnPage('/');
        $I->see('Logout (unit_test)');
    }
    
    public function passAmountPage(\FunctionalTester $I)
    {
        $user = \app\models\User::userRegistration('unit_test');
        $I->amLoggedInAs('unit_test');
        
        $I->amOnRoute('billing-operations/create');
        
        $I->submitForm('#billing-operations-form', [
            'BillingOperations[login]' => 'admin',
            'BillingOperations[amount]' => '100',
        ]);
        
        $I->see('Logout (unit_test)');
        $I->dontSeeElement('form#billing-operations-form');
    }
}