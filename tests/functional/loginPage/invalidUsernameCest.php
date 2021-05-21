<?php

class invalidUsernameCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/cms/user/login');
        $I->see('Please Sign In', '.kt-login__title');
    }

    // tests for Invalid Email in Username
    public function givesRightErrorMessage(FunctionalTester $I)
    {
        // Right Message?
        $I->fillField('#loginform-username', 'asdf');
        $I->click('#kt_login_signin_submit');
        $I->see('Username is not a valid email address.');
    }

    public function doesNotAllowLogin(FunctionalTester $I)
    {
        // Does not login?
        $I->dontSeeCurrentUrlEquals('/user/user/index');
    }
}
