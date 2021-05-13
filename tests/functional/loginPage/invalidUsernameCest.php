<?php

use skyline\yii\user\tests\fixtures\UserFixture;

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

    public function grid(FunctionalTester $I)
    {
        $I->haveFixtures([
            'twentyOneUsers' => [
                'class' => UserFixture::classname(),
                'dataFile' => codecept_data_dir() . 'user-21rows.php',
            ],
        ]);
        $I->amOnPage('/cms/user/index');
        $I->see('cat');
    }
}
