<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\enterpriseTheme\tests\codeception\fixtures;

use humhub\modules\enterpriseTheme\models\Type;
use yii\test\ActiveFixture;

class TypeFixture extends ActiveFixture
{
    public $modelClass = Type::class;
    public $dataFile = '@enterprise-theme/tests/codeception/fixtures/data/type.php';
}
