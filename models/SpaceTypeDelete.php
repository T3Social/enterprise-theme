<?php

namespace humhub\modules\enterpriseTheme\models;

use Yii;
use yii\base\Model;

class SpaceTypeDelete extends Model
{

    public $space_type_id;

    public function rules()
    {
        return [
            ['space_type_id', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'space_type_id' => Yii::t('EnterpriseThemeModule.base', 'Space Category'),
        ];
    }

}
