<?php

// Make sure to 'use' a fixture if you want to include it below
use skyline\yii\user\tests\fixtures\UserFixture;

class loginWorksCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/cms/user/login');
        $I->see('Please Sign In', '.kt-login__title');
    }

    // tests
    public function userCanLogin(FunctionalTester $I)
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


        $I->fillField(['name' => 'LoginForm[username]'], 'jdoe@skylinenet.net');
        $I->fillField(['name' => 'LoginForm[password]'], 'temporary');
        $I->click('#kt_login_signin_submit');
        $I->seeCurrentUrlEquals('/user/user/index');
    }
}
