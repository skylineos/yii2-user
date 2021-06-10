<?php

namespace crud;

use skyline\yii\user\tests\fixtures\UserFixture;

class updatePageCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->haveFixtures([
            'user' => [
                // Refers to tests/fixtures/{classname}
                'class' => UserFixture::classname(),
                // refers to tests/_data/user.php - you can load whichever one you like so long as it
                // applies to the 'class' above
                'dataFile' => codecept_data_dir() . 'user-21rows.php',
            ],
        ]);

        $I->amOnPage('/user/user/update?id=1');
    }

    // tests about seeing items on the create page
    public function displaysMessageAboutEmailedInstructions(\FunctionalTester $I)
    {
        $I->dontSee('An email containing instructions for completing the account registration will 
        be sent to the user when you click \'Create\'');
    }

    public function hasAppropriateLabels(\FunctionalTester $I)
    {
        $I->seeElement('label', ['for' => 'user-name']);
        $I->seeElement('label', ['for' => 'user-email']);
        $I->seeElement('label', ['for' => 'user-status']);
    }

    public function hasAppropriateFields(\FunctionalTester $I)
    {
        $I->seeElement('input', ['name' => '_csrf']);
        $I->seeElement('input', ['name' => 'User[name]']);
        $I->seeElement('input', ['name' => 'User[email]']);
        $I->seeElement('select', ['name' => 'User[status]']);

        $I->seeInFormFields('form[id=user-form]', [
            'User[name]' => 'John Doe 1',
            'User[email]' => 'johndoe1@skylinenet.net',
            'User[status]' =>  \skyline\yii\user\models\User::STATUS_ACTIVE,
        ]);
    }

    public function hasCreateAndCancelButtons(\FunctionalTester $I)
    {
        $I->see('update', 'button');
        $I->seeLink('Cancel', '/cms');
    }

    public function getErrorsOnEmptyFields(\FunctionalTester $I)
    {
        $I->fillField(['name' => 'User[name]'], '');
        $I->fillField(['name' => 'User[email]'], '');
        $I->click('Update');
        $I->see('Name cannot be blank.', '.help-block');
        $I->see('Email cannot be blank.', '.help-block');
    }

    public function getErrorOnBadEmail(\FunctionalTester $I)
    {
        $I->fillField(['name' => 'User[email]'], 'Not an email address');
        $I->click('Update');
        $I->see('Email is not a valid email address.', '.help-block');
    }

    public function goodData(\FunctionalTester $I)
    {
        $I->click('Update');
        $I->seeCurrentUrlEquals('/cms');
    }
}
