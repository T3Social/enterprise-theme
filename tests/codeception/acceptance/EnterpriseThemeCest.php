<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace enterpriseTheme\acceptance;

use enterpriseTheme\AcceptanceTester;
use humhub\libs\DynamicConfig;
use humhub\modules\enterpriseTheme\models\SpaceType;
use humhub\modules\ui\view\helpers\ThemeHelper;

class EnterpriseThemeCest
{

    public function _before()
    {
        // We should activate the theme manually here again, because after activation in \enterpriseTheme\Module::enable()
        // the global setting `theme` is reset to default value on calling the Test by some reason.
        $theme = ThemeHelper::getThemeByName('enterprise');
        if ($theme !== null) {
            $theme->activate();
            DynamicConfig::rewrite();
        }

        // Link all spaces to test type
        SpaceType::updateAll(['space_type_id' => 1]);
    }

    public function testSpaceChooser(AcceptanceTester $I)
    {
        $I->wantTo('choose a space');
        $I->amAdmin();

        $I->amOnDashboard();

        $spaceChooserSelector = '.nav-space-chooser';
        $I->see('Test spaces', $spaceChooserSelector);
        $I->see('Space 1', $spaceChooserSelector);

        $I->fillField('#space-menu-search', 'Space 2');
        $I->dontSee('Space 1', $spaceChooserSelector);
    }

    public function testSpaceType(AcceptanceTester $I)
    {
        $I->wantTo('create a space category');
        $I->amAdmin();

        // Create a new Space Type
        $I->amOnRoute(['/enterprise-theme/admin/index']);
        $I->click('Create new category');
        $I->waitForText('Create new space category');
        $I->fillField('Type[title]', 'New space category');
        $I->fillField('Type[item_title]', 'New space item');
        $I->fillField('Type[sort_key]', '200');
        $I->click('Save');
        $I->waitForText('New space category');

        // Move Space 2 into new Type
        $I->amOnRoute(['/admin/space/index']);
        $I->waitForText('Manage spaces');
        $I->jsClick('tr[data-key="2"] button.dropdown-toggle');
        $I->waitForText('Edit');
        $I->click('Edit', 'tr[data-key="2"]');
        $I->waitForText('Space settings');
        $I->click('Category', '.tab-menu');
        $I->waitForText('Change category');
        $I->selectOption('SpaceType[space_type_id]', ['value' => '2']);
        $I->click('Save');
        $I->seeSuccess('Saved');

        // Check the Space 2 has been moved into the new Type
        $I->amOnDashboard();
        $I->waitForText('TEST SPACES');
        $I->dontSee('Space 2', 'ul#space-menu-type-1');
        $I->see('Space 1', 'ul#space-menu-type-1');
        $I->waitForText('NEW SPACE CATEGORY');
        $I->see('Space 2', 'ul#space-menu-type-2');
        $I->dontSee('Space 1', 'ul#space-menu-type-2');
    }
}
