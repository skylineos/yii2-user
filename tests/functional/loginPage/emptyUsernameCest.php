<?php

namespace loginPage;

class emptyUsernameCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnPage('/cms/user/login');
        $I->see('Please Sign In', '.kt-login__title');
    }

    // tests for Empty Email in username
    public function givesRightErrorMessage(\FunctionalTester $I)
    {
        // Right message?
        $I->fillField('#loginform-password', 'asdf');
        $I->click('#kt_login_signin_submit');
        $I->see('Username cannot be blank.');
    }

    public function doesNotAllowLogin(\FunctionalTester $I)
    {
        // Does not login?
        $I->dontSeeCurrentUrlEquals('/user/user/index');
    }
}
