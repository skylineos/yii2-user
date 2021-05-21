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
    public function linkExists(FunctionalTester $I)
    {
        // Link is correct
        $I->seeLink('Forget Password ?');
        $I->seeLink('Forget Password ?','javascript:;');

        // LINK WORKS? --- ERROR for: $I->click('Forget Password ?');
        // On Login Page, the link "Forget Password ?" requires a javascript response that 
        // we cannot mock in codeception.
    }
}
