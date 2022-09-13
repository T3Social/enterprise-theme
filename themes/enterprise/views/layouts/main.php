<?php

use humhub\assets\AppAsset;
use humhub\libs\LogoImage;
use humhub\modules\enterpriseTheme\assets\EnterpriseThemeAsset;
use humhub\modules\enterpriseTheme\widgets\Chooser;
use humhub\modules\enterpriseTheme\widgets\SearchWidget;
use humhub\modules\notification\widgets\Overview;
use humhub\modules\user\widgets\AccountTopMenu;
use humhub\widgets\NotificationArea;
use humhub\libs\Html;
use humhub\widgets\TopMenu;
use humhub\widgets\TopMenuRightStack;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
EnterpriseThemeAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title><?= $this->pageTitle; ?></title>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <?php $this->head() ?>
        <?= $this->render('head'); ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
    <div id="wrapper">

        <div id="sidebar-wrapper">
            <?php if (LogoImage::hasImage()) : ?>
                <a class="navbar-brand hidden-xs" href="<?= Yii::$app->homeUrl; ?>">
                    <img id="img-logo" class="img-rounded"
                         src="<?= LogoImage::getUrl(600, 600); ?>"
                         alt="<?= Html::encode(Yii::$app->name) ?>"/>
                </a>
            <?php else: ?>
                <a class="navbar-brand" href="<?= Yii::$app->homeUrl; ?>" id="text-logo">
                    <?= Html::encode(Yii::$app->name); ?>
                </a>
            <?php endif; ?>

            <?= TopMenu::widget(); ?>
            <div id="hide-sidebar">
                <a href="#menu-toggle" class="menu-toggle" class="dropdown-toggle"
                   aria-label="<?= Yii::t('EnterpriseThemeModule.base', 'Hide sidebar') ?>">
                    <i class="fa fa-times"></i>
                </a>
            </div>

            <?= Chooser::widget(['lazyLoad' => false]); ?>
        </div>

        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-md">

                        <div id="topbar-first" class="topbar">
                            <div id="rp-nav" class="nav pull-left">
                                <ul class="nav pull-left navigation-bars">
                                    <li class="dropdown">
                                        <a href="#menu-toggle" class="menu-toggle"
                                           aria-label="<?= Yii::t('EnterpriseThemeModule.base', 'Show sidebar') ?>"
                                           class="dropdown-toggle">
                                            <i class="fa fa-bars"></i>
                                        </a>
                                    </li>
                                </ul>
                                <div class="menu-seperator"></div>
                            </div>
                            <?= SearchWidget::widget(); ?>
                            <div class="topbar-actions pull-right">

                                <ul class="nav pull-left" id="search-menu-nav">
                                    <?= TopMenuRightStack::widget(); ?>
                                </ul>

                                <div class="menu-seperator"></div>
                                <div class="notifications">
                                    <?=
                                    NotificationArea::widget(['widgets' => [
                                        [Overview::class, [], ['sortOrder' => 10]],
                                    ]]);
                                    ?>
                                </div>

                                <div class="menu-seperator"></div>
                                <?= AccountTopMenu::widget(['showUserName' => false]); ?>
                            </div>
                        </div>

                        <div class="content">
                            <?= $content; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>
