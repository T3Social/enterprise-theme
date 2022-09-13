<?php

use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\space\widgets\SpaceChooserItem;
use humhub\modules\space\models\Space;
use humhub\modules\ui\view\components\View;
use humhub\modules\enterpriseTheme\models\Type;
use humhub\modules\space\models\Membership;

/**
 * @var $this View
 * @var $currentSpace Space
 * @var $noSpaceHtml string
 *
 * @var $memberships Membership[]
 * @var $followSpaces Space[]
 * @var $typeMembershipMap array
 *
 * @var $canCreateSpace boolean
 * @var $canAccessDirectory boolean
 * @var $createSpaceTypes Type[]
 *
 * @var $renderedItems string
 */

?>

<ul class="nav nav-pills nav-stacked nav-space-chooser" id="space-menu-dropdown"  data-action-component="space.chooser.SpaceChooser">

    <li class="search">
        <form action="" class="dropdown-controls">
            <input type="text" id="space-menu-search" class="form-control form-search" autocomplete="off"
                   placeholder="<?= Yii::t('SpaceModule.chooser', 'Filter'); ?>"
                   title="<?= Yii::t('SpaceModule.chooser', 'Search for spaces'); ?>">

            <div class="search-reset" id="space-search-reset"><i class="fa fa-times-circle"></i></div>
        </form>
    </li>

    <?php foreach ($typeMembershipMap as $entry) : ?>
        <?php if(empty($entry['memberships']) && empty($entry['following']) && !in_array($entry['spaceType'], $createSpaceTypes)) {continue;} ?>
        <li class="title space-type-nav-title">
            <i class="fa fa-caret-up"></i>&nbsp;
            <?= Html::encode($entry['spaceType']->title); ?>
            <?php if (in_array($entry['spaceType'], $createSpaceTypes)) : ?>
                <span class="title-link">
                    <a href="#" data-action-click="ui.modal.load" aria-label="<?= Yii::t('SpaceModule.chooser', 'Create new {spaceCategory}', ['spaceCategory' => Html::encode($entry['spaceType']->item_title)]) ?>" data-action-url="<?= Url::to(['/enterprise-theme/create-space/create', 'type_id' => $entry['spaceType']->id]) ?>">
                        <i class="fa fa-plus-square"></i>
                    </a>
                </span>
            <?php endif; ?>
        </li>
        <li>
            <div class="space-type-nav-container">
                <ul id="space-menu-type-<?= $entry['spaceType']->id ?>" class="space-entries">
                    <?php foreach ($entry['visibleMemberships'] as $membership): ?>
                        <?= SpaceChooserItem::widget(['space' => $membership->space, 'updateCount' => $membership->countNewItems(), 'isMember' => true]); ?>
                    <?php endforeach; ?>
                    <?php foreach ($entry['visibleFollowing'] as $followingSpace): ?>
                        <?= SpaceChooserItem::widget(['space' => $followingSpace, 'isFollowing' => true]); ?>
                    <?php endforeach; ?>

                    <?php foreach ($entry['hiddenMemberships'] as $membership): ?>
                        <?= SpaceChooserItem::widget(['space' => $membership->space, 'updateCount' => $membership->countNewItems(), 'isMember' => true, 'visible' => false]); ?>
                    <?php endforeach; ?>
                    <?php foreach ($entry['hiddenFollowing'] as $followingSpace): ?>
                        <?= SpaceChooserItem::widget(['space' => $followingSpace, 'isFollowing' => true, 'visible' => false]); ?>
                    <?php endforeach; ?>

                    <?php if(! empty($entry['hiddenMemberships']) || ! empty($entry['hiddenFollowing'])) : ?>
                        <li class="space-type-visibility">
                            <a href="#" class="space-type-visibility-control" data-action-click="toggleShowMore" data-action-target="#space-menu-dropdown">
                                <i class="fa fa-angle-down"></i>
                                <?php $showMore =  Yii::t('EnterpriseThemeModule.base', 'Show {count} more',
                                    ['count' => (count($entry['hiddenMemberships']) + count($entry['hiddenFollowing']))]) ?>

                                <span data-show-less="<?= Yii::t('EnterpriseThemeModule.base', 'Show less') ?>" data-show-more="<?= $showMore ?>">
                                    <?= $showMore ?>
                                </span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
<ul id="space-chooser-result" class="nav nav-pills nav-stacked nav-space-chooser space-entries">

</ul>
<ul class="nav nav-pills nav-stacked nav-space-chooser space-entries">
    <li id="space-menu-remote-search"></li>
</ul>
