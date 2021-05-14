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
        $I->see('An email containing instructions for completing the account registration will be sent to the user when you click \'Create\'');
    }

    public function hasLabelName(FunctionalTester $I)
    {
        //$I->seeElement('label', ['class' => 'col-form-label']);
        $I->seeElement('div', ['class' => 'form-group field-user-name required-field']);

    }


}
