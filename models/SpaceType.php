<?php

namespace humhub\modules\enterpriseTheme\models;

use humhub\modules\space\models\Space;
use Yii;

/**
 * @inheritdoc
 *
 * @property integer $space_type_id
 */
class SpaceType extends Space
{

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['changeType'] = ['space_type_id'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [['space_type_id', 'validateSpaceType']]);
    }

    public function validateSpaceType($attribute)
    {
        $spaceType = Type::findOne(['id' => (int) $this->$attribute]);
        if (!$spaceType || !$spaceType->canCreateSpace()) {
            $this->addError($attribute, Yii::t('EnterpriseThemeModule.base', 'You have no permission to create a space with the category!'));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'space_type_id' => Yii::t('EnterpriseThemeModule.base', 'Category'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'space_type_id' => Yii::t('EnterpriseThemeModule.base', 'Select under which category this Space should be listed. You can move a Space from category to category at any time, if you have the necessary permissions.')
        ];
    }

}