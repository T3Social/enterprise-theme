<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>


<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('EnterpriseThemeModule.base', '<strong>Delete</strong> space category'); ?>
    </div>

    <div class="panel-body">
        <p>
            <?= Yii::t('EnterpriseThemeModule.base', 'To delete the space category <strong>"{category}"</strong> you need to set an alternative category for existing spaces:', ['{category}' => Html::encode($type->title)]); ?>
        </p>

        <?php
        $form = ActiveForm::begin([])
        ?>
        <?= $form->field($model, 'space_type_id')->dropDownList($alternativeTypes) ?>

        <?= Html::submitButton(Yii::t('base', 'Delete'), ['class' => 'btn btn-danger']) ?>

        <?php ActiveForm::end() ?>
    </div>
</div>