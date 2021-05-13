<?php

class loginWorksCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/cms/user/login');
        $I->see('Please Sign In', '.kt-login__title');
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
        $I->fillField(['name' => 'LoginForm[username]'], 'lwelch@skylinenet.net');
        $I->fillField(['name' => 'LoginForm[password]'], 'admin1234');
        $I->click('#kt_login_signin_submit');
        sleep(2.0);
        $I->seeCurrentUrlEquals('/cms');
    }
}
