<?php

namespace humhub\modules\enterpriseTheme\models;

use Yii;
use humhub\modules\enterpriseTheme\permissions\CreateSpaceType;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "space_type".
 *
 * @property integer $id
 * @property string $title
 * @property string $item_title
 * @property integer $sort_key
 * @property integer $show_in_directory
 */
class Type extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'space_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'item_title', 'sort_key'], 'required'],
            [['sort_key', 'show_in_directory'], 'integer'],
            [['title', 'item_title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('EnterpriseThemeModule.base', 'ID'),
            'title' => Yii::t('EnterpriseThemeModule.base', 'Title'),
            'item_title' => Yii::t('EnterpriseThemeModule.base', 'Item Title'),
            'sort_key' => Yii::t('EnterpriseThemeModule.base', 'Sortorder'),
            'show_in_directory' => Yii::t('EnterpriseThemeModule.base', 'Show In Directory'),
        ];
    }

    /**
     * Checks if current user can a space of this type
     */
    public function getCreateSpacePermission()
    {
        $permission = Yii::createObject(CreateSpaceType::class);
        $permission->spaceType = $this;
        return $permission;
    }

    public function canCreateSpace()
    {
        if (Yii::$app->user->isAdmin()) {
            return true;
        }
        return (Yii::$app->user->permissionManager->can($this->getCreateSpacePermission()));
    }

}
