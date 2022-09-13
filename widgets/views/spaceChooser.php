<?php

use humhub\modules\enterpriseTheme\models\Type;
use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;
use humhub\modules\space\widgets\Image;
use humhub\modules\space\widgets\SpaceChooserItem;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\modules\ui\view\components\View;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this View
 * @var $currentSpace Space
 * @var $noSpaceHtml string
 *
 * @var $memberships Membership[]
 * @var $followSpaces Space[]
 *
 * @var $canCreateSpace boolean
 * @var $canAccessDirectory boolean
 * @var $createSpaceTypes Type[]
 *
 * @var $renderedItems string
 */

?>

<li class="dropdown">
    <a href="#" id="space-menu" class="dropdown-toggle" data-toggle="dropdown">
        <!-- start: Show space image and name if chosen -->
        <?php if ($currentSpace) : ?>
            <?= Image::widget(['space' => $currentSpace, 'width' => 32, 'htmlOptions' => ['class' => 'current-space-image']]); ?>
            <b class="caret"></b>
        <?php endif; ?>

        <?php if (!$currentSpace) : ?>
            <?= $noSpaceHtml ?>
        <?php endif; ?>
        <!-- end: Show space image and name if chosen -->
    </a>

    <ul class="dropdown-menu" id="space-menu-dropdown">
        <li>
            <form action="" class="dropdown-controls">
                <div <?= $canAccessDirectory ? 'class="input-group"' : '' ?>>
                    <input type="text" id="space-menu-search" class="form-control" autocomplete="off"
                           placeholder="<?= Yii::t('SpaceModule.chooser', 'Search') ?>"
                           title="<?= Yii::t('SpaceModule.chooser', 'Search for spaces') ?>">
                    <?php if ($canAccessDirectory) : ?>
                        <span id="space-directory-link" class="input-group-addon">
                            <a href="<?= Url::to(['/space/spaces']) ?>">
                                <?= Icon::get('directory') ?>
                            </a>
                        </span>
                    <?php endif; ?>
                    <div class="search-reset" id="space-search-reset"><?= Icon::get('times-circle') ?></div>
                </div>
            </form>
        </li>

        <li class="divider"></li>
        <li>
            <ul class="media-list notLoaded" id="space-menu-spaces">
                <?php foreach ($memberships as $membership): ?>
                    <?= SpaceChooserItem::widget(['space' => $membership->space, 'updateCount' => $membership->countNewItems(), 'isMember' => true, 'spaceTypes' => $spaceTypes]); ?>
                <?php endforeach; ?>
                <?php foreach ($followSpaces as $followSpace): ?>
                    <?= SpaceChooserItem::widget(['space' => $followSpace, 'isFollowing' => true, 'spaceTypes' => $spaceTypes]); ?>
                <?php endforeach; ?>
            </ul>
        </li>
        <li class="remoteSearch">
            <ul id="space-menu-remote-search" class="media-list notLoaded"></ul>
        </li>

        <?php if (!empty($createSpaceTypes)): ?>
            <li>
                <div class="dropdown-footer">
                    <?php foreach ($createSpaceTypes as $type): ?>
                        <a href="#" class="btn btn-info btn-sm" data-action-click="ui.modal.load"
                           data-action-url="<?= Url::to(['/enterprise-theme/create-space/create', 'type_id' => $type->id]) ?>">
                            <i class="fa fa-plus"></i> <?= Html::encode($type->item_title) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </li>
        <?php endif; ?>
    </ul>
</li>
