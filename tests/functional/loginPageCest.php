<?php

use skyline\yii\user\tests\fixtures\UserFixture;

class loginPageCest
{
    public function _before(FunctionalTester $I)
    {
    }

    // tests
    public function defaultForm(FunctionalTester $I)
    {
        $I->amOnPage('/cms/user/login');
        $I->see('Please Sign In', '.kt-login__title');

        //
        $I->fillField('#loginform-username', 'asdf');
        $I->click('#kt_login_signin_submit');
        $I->see('Username is not a valid email address.');
    }
}
