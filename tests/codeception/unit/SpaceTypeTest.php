<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\enterpriseTheme\tests\codeception\unit;

use humhub\modules\enterpriseTheme\models\SpaceType;
use humhub\modules\enterpriseTheme\models\Type;
use tests\codeception\_support\HumHubDbTestCase;


class SpaceTypeTest extends HumHubDbTestCase
{

    public function testSpaceTypeCreation()
    {
        $this->becomeUser('Admin');

        $type = new Type([
            'title' => 'Test Category title',
            'item_title' => 'Item test Category title',
            'sort_key' => 1,
        ]);

        $this->assertTrue($type->save());

        /* @var $type Type */
        $type = Type::findOne(['id' => 2]);
        $this->assertEquals($type->title, 'Test Category title');

        /* @var $space SpaceType */
        $space = SpaceType::findOne(['id' => 1]);
        $space->space_type_id = $type->id;
        $this->assertTrue($space->save());
    }
}
