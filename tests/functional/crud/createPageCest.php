<?php

class createPageCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/user/user/create');
    }

    // tests about seeing items on the create page
    public function displaysMessageAboutEmailedInstructions(FunctionalTester $I)
    {
        $I->see('An email containing instructions for completing the account registration will 
        be sent to the user when you click \'Create\'');
    }

    public function hasAppropriateLabels(FunctionalTester $I)
    {
        $I->seeElement('label', ['for' => 'user-name']);
        $I->seeElement('label', ['for' => 'user-email']);
        $I->seeElement('label', ['for' => 'user-status']);
    }

    public function hasAppropriateFields(FunctionalTester $I)
    {
        $I->seeElement('input', ['name' => '_csrf']);
        $I->seeElement('input', ['name' => 'User[name]']);
        $I->seeElement('input', ['name' => 'User[email]']);
        $I->seeElement('select', ['name' => 'User[status]']);
    }

    public function hasCreateAndCancelButtons(FunctionalTester $I)
    {
        $I->see('Create', 'button');
        $I->seeLink('Cancel', '/cms');
    }

    public function getErrorsOnEmptyFields(FunctionalTester $I)
    {
        $I->click('Create');
        $I->see('Name cannot be blank.', '.help-block');
        $I->see('Email cannot be blank.', '.help-block');
    }

    public function getErrorOnBadEmail(FunctionalTester $I)
    {
        $I->fillField(['name' => 'User[name]'], 'John Doe');
        $I->fillField(['name' => 'User[email]'], 'Not an email address');
        $I->click('Create');
        $I->see('Email is not a valid email address.', '.help-block');
    }

    public function goodData(FunctionalTester $I)
    {
        $I->fillField(['name' => 'User[name]'], 'John Doe');
        $I->fillField(['name' => 'User[email]'], 'jdoe@skylinenet.net');
        $I->click('Create');
        $I->seeCurrentUrlEquals('/cms');
    }
}
