<?php

namespace humhub\modules\enterpriseTheme\controllers;

use Yii;
use humhub\modules\space\controllers\CreateController;
use humhub\modules\enterpriseTheme\models\Type;
use yii\base\Exception;

/**
 * CreateSpaceController
 *
 * @author luke
 */
class CreateSpaceController extends CreateController
{

    /**
     * @inheritdoc
     */
    protected function createSpaceModel()
    {
        $type = Type::findOne(['id' => Yii::$app->request->get('type_id')]);
        if ($type === null) {
            throw new Exception("Could not find space category!");
        }

        if (!$type->canCreateSpace()) {
            throw new Exception("Insuffient permissions!");
        }


        $model = parent::createSpaceModel();
        $model->space_type_id = $type->id;
        return $model;
    }

    public function getTypeTitle($model)
    {
        $type = Type::findOne(['id' => $model->space_type_id]);
        if ($type !== null) {
            return $type->item_title;
        }

        return "undefined";
    }

}
