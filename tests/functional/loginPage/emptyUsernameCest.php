<?php

class emptyUsernameCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/cms/user/login');
        $I->see('Please Sign In', '.kt-login__title');
    }

    // tests
    public function givesRightErrorMessage(FunctionalTester $I)
    {
        // Empty Email in Username
        $I->fillField('#loginform-password', 'asdf');
        $I->click('#kt_login_signin_submit');
        $I->see('Username cannot be blank.');
    }
}