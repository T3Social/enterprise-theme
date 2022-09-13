<?php

use humhub\libs\Html;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\widgets\Button;
use yii\helpers\Url;

?>
<div class="nav pull-left nav-search">
    <?= Html::beginForm(Url::to(['/search/search/index']), 'GET') ?>
    <div class="form-group form-group-search">
        <?= Button::defaultType()->submit()->style('width:25px;height:25px;position:absolute;top:4px;left:0;opacity:0') ?>
        <?= Html::textInput('SearchForm[keyword]', '', ['placeholder' => Yii::t('base', 'Search'), 'title' => Yii::t('SearchModule.base', 'Search for user, spaces and content'), 'class' => 'form-control form-search', 'id' => 'search-input-field']); ?>
        <?= Html::submitButton(Yii::t('base', 'Search'), ['class' => 'btn btn-default btn-sm form-button-search hidden']) ?>
    </div>
    <?= Html::endForm() ?>
</div>