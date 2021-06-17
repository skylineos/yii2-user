<?php

namespace loginPage;

class forgotPasswordCest
{
    // Verify right page
    public function _before(\FunctionalTester $I)
    {
        $I->amOnPage('/cms/user/login');
        $I->see('Please Sign In', '.kt-login__title');
    }

    // tests
    public function linkExists(\FunctionalTester $I)
    {
        // Link is correct
        $I->seeLink('Forget Password ?');
        $I->seeLink('Forget Password ?', 'javascript:;');
    }

    public function forgotPasswordFormExists(\FunctionalTester $I)
    {
        $I->seeElement('input', ['name' => 'RequestPasswordReset[email]']);
        $I->seeElement('#kt_login_forgot_cancel');
        $I->seeElement('#kt_login_forgot_submit');

        // Quite difficult to actually test javascript behaviours/email sending from here
    }
}
