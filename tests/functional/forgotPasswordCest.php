<?php

class forgotPasswordCest
{
    // Verify right page
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/cms/user/login');
        $I->see('Please Sign In', '.kt-login__title');
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
        // Link is correct
        $I->seeLink('Forget Password ?');
        // $I->seeLink('Forget Password ?','javascript:;');

        // Link works
        // $I->click('Forget Password ?','#kt-login__link');
        // $I->see('Forgotten Password ?');
    }
}
