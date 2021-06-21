<?php

namespace passwordRecovery;

use skyline\yii\user\tests\fixtures\UserFixture;

class resetCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->haveFixtures([
            'user' => [
                // Refers to tests/fixtures/{classname}
                'class' => UserFixture::classname(),
                // refers to tests/_data/user.php - you can load whichever one you like so long as it
                // applies to the 'class' above
                'dataFile' => codecept_data_dir() . 'user.php',
            ],
        ]);

        $I->amOnPage('/cms/user/change-password?email=jdoe@skylinenet.net&token=aUpjerPJngMz6yKE4P.J5y$13$2AhSLqVX.XaUpjerPJngMz6yKE4P.J');
    }

    // tests
    public function emptyRequestParams(\FunctionalTester $I)
    {
        $I->amOnPage('/cms/user/change-password');
        $I->see('Forbidden:');
    }

    public function seeTitle(\FunctionalTester $I)
    {
        $I->see('Welcome back. Use the form below to reset your password.');
    }

    public function properLables(\FunctionalTester $I)
    {
        $I->seeElement('label', ['for' => 'resetpassword-password']);
        $I->seeElement('label', ['for' => 'resetpassword-verifypassword']);
        $I->see('New Password');
        $I->see('Confirm Your Password');
    }

    public function properFields(\FunctionalTester $I)
    {
        $I->seeElement('input', ['name' => '_csrf']);
        $I->seeElement('input', ['name' => 'ResetPassword[password]']);
        $I->seeElement('input', ['name' => 'ResetPassword[verifyPassword]']);
    }

    public function properButtons(\FunctionalTester $I)
    {
        $I->see('Reset Password', 'button');
        $I->seeLink('Cancel', '/');
    }

    public function mismatchedPasswords(\FunctionalTester $I)
    {
        $I->fillField(['name' => 'ResetPassword[password]'], 'my-first-password');
        $I->fillField(['name' => 'ResetPassword[verifyPassword]'], 'my-second-password');
        $I->click('Reset Password');
        $I->see('Password must be equal to "Verify Password".');
    }

    public function matchedPasswords(\FunctionalTester $I)
    {
        $password = 'password';

        $I->fillField(['name' => 'ResetPassword[password]'], $password);
        $I->fillField(['name' => 'ResetPassword[verifyPassword]'], $password);
        $I->click('Reset Password');
        $I->seeCurrentUrlEquals('/cms');
    }
}
