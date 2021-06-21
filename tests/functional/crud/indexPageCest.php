<?php

namespace crud;

use skyline\yii\user\tests\fixtures\UserFixture;
use Codeception\Util\Locator;

class indexPageCest
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

        $I->amOnPage('/user/user/index');
    }

    // All elements should be present
    public function basePageElements(\FunctionalTester $I)
    {
        $I->wantTo('Check header content and summary');
        $I->see('User Accounts', 'h3');
        $I->see('Showing 1-20 of 21 items.', '.summary');

        // 22 rows including titles and filter
        $I->seeNumberOfElements('tr', 22);

        // Headers
        $I->wantTo('Check for the sortable headers');
        $I->seeLink('Email', 'sort=email');
        $I->seeLink('Name', 'sort=name');
        $I->seeLink('Status', 'sort=status');
        $I->seeLink('Last Login', 'sort=lastLogin');
        $I->seeLink('Last Modified', 'sort=lastModified');

        // Filters
        $I->wantTo('Confirm the appropriate column filters exist');
        $I->seeElement('input', ['name' => 'UserSearch[email]']);
        $I->seeElement('input', ['name' => 'UserSearch[name]']);
        $I->seeElement('select', ['name' => 'UserSearch[status]']);
        $I->dontSeeElement('input', ['name' => 'UserSearch[lastLogin]']);
        $I->dontSeeElement('input', ['name' => 'UserSearch[lastModified]']);

        // Row buttons
        $I->wantTo('Verify actionColumn fields');

        // Any given row, based on provided fixture
        $I->wantTo('Verify dataColumns are being populated appropriately');
        $I->seeLink("johndoe1@skylinenet.net", 'mailto:johndoe1@skylinenet.net');
        $I->see("John Doe 1", '.td-user-name');
        $I->see('Active', '.td-user-status');
        $I->seeDateInPast($I->grabTextFrom('.td-user-lastLogin'));
        $I->seeDateInPast($I->grabTextFrom('.td-user-lastModified'));

        /**
         * Pagination is a pass through
         * No need to test for elements in this module
         * No need to repeat element display on pagination link following
         */
    }

    public function clickUpdate(\FunctionalTester $I)
    {
        $locatorUrl = '/cms/user/update?id=1';

        $I->seeElement('a', ['href' => $locatorUrl]);
        $I->click(Locator::href($locatorUrl));
        $I->seeInCurrentUrl('user/update?id=1');
    }

    public function gridFilters(\FunctionalTester $I)
    {
        // Javascript, pressing enter requires web driver
    }

    #public function clickDelete(\FunctionalTester $I)
    #{
        // Because of confirmation prompt, requires web driver
    #}
}
