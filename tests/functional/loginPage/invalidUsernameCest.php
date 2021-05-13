<?php

class invalidUsernameCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/cms/user/login');
        $I->see('Please Sign In', '.kt-login__title');
    }

    // tests
    public function givesRightErrorMessage(FunctionalTester $I)
    {
        // Invalid Email in Username
        $I->fillField('#loginform-username', 'asdf');
        $I->click('#kt_login_signin_submit');
        $I->see('Username is not a valid email address.');
    }
}
