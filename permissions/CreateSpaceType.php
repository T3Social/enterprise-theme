<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2019 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\enterpriseTheme\permissions;

use humhub\libs\BasePermission;
use humhub\modules\enterpriseTheme\models\Type;
use Yii;

class CreateSpaceType extends BasePermission
{

    /**
     * @var Type
     */
    public $spaceType;

    /**
     * @inheritdoc
     */
    protected $moduleId = 'enterprise-theme';

    /**
     * @inheritdoc
     */
    protected $defaultState = self::STATE_ALLOW;

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return 'create_space_type_' . $this->spaceType->id;
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return Yii::t('EnterpriseThemeModule.base', 'Create spaces of category: {category}', ['category' => $this->spaceType->item_title]);
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return Yii::t('EnterpriseThemeModule.base', 'Users can create spaces of category: {category}', ['category' => $this->spaceType->item_title]);
    }

}
