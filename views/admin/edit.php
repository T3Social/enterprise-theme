<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="panel panel-default">
    <?php if (!$type->isNewRecord) : ?>
        <div
            class="panel-heading"><?php echo Yii::t('EnterpriseThemeModule.base', '<strong>Edit</strong> space category'); ?></div>
    <?php else: ?>
        <div
            class="panel-heading"><?php echo Yii::t('EnterpriseThemeModule.base', '<strong>Create</strong> new space category'); ?></div>
    <?php endif; ?> 
     <?= \humhub\modules\admin\widgets\SpaceMenu::widget(); ?>     
    <div class="panel-body">
        <div class="clearfix">
            <?php echo Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;&nbsp;' . Yii::t('EnterpriseThemeModule.base', 'Back to overview'), 
                    Url::to(['index']), array('class' => 'btn btn-default pull-right')); ?>
        </div>
        <?php
        $form = ActiveForm::begin([
            'id' => 'login-form',
        ])
        ?>
        <?= $form->field($type, 'title')->hint(Yii::t('EnterpriseThemeModule.base', 'e.g. Projects')) ?>
        <?= $form->field($type, 'item_title')->hint(Yii::t('EnterpriseThemeModule.base', 'e.g. Project')) ?>
        <?= $form->field($type, 'sort_key') ?>
        <?= $form->field($type, 'show_in_directory')->checkbox() ?>

        <?= Html::submitButton(Yii::t('base', 'Save'), ['class' => 'btn btn-primary']) ?>

        <?php if ($canDelete): ?>
            <?= Html::a(Yii::t('base', 'Delete'), Url::toRoute(['delete', 'id' => $type->id]), array('class' => 'btn btn-danger')); ?>
        <?php endif; ?>

        <?php ActiveForm::end() ?>

    </div>
</div>