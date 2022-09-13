<?php

use humhub\modules\content\assets\ContainerHeaderAsset;
use humhub\modules\content\controllers\ContainerImageController;
use humhub\modules\file\widgets\Upload;
use humhub\modules\space\modules\manage\widgets\DefaultMenu;

ContainerHeaderAsset::register($this);

$uploadUrl = $space->createUrl('/space/manage/image/upload');
$deleteUrl = $space->createUrl('/space/manage/image/delete', ['type' => ContainerImageController::TYPE_PROFILE_IMAGE]);
$cropUrl =$space->createUrl('/space/manage/image/crop');

$profileImageUpload = Upload::withName('spacefiles', ['url' => $uploadUrl]);

?>

<div id="space-image-settings" class="panel panel-default">
    <div class="panel-heading"><?= Yii::t('EnterpriseThemeModule.base', '<strong>Change</strong> space image') ?></div>

    <style>
        <!-- Just for migration purposes -->
        #space-image-settings .image-upload-buttons {
            display:block !important;
        }
    </style>

    <?= DefaultMenu::widget(['space' => $space]) ?>

    <div class="panel-body" data-ui-widget="content.container.Header" data-ui-init>
        <strong><?= Yii::t('EnterpriseThemeModule.base', 'Current image') ?></strong>

        <br><br>

        <div class="image-upload-container profile-user-photo-container" style="width: 140px; height: 140px;">

            <?php if ($space->getProfileImage()->hasImage()) : ?>
                <a data-ui-gallery="spaceHeader" href="<?= $space->profileImage->getUrl('_org') ?>">
                    <?= $space->getProfileImage()->render( 140,  ['class' => 'img-profile-header-background profile-user-photo', 'link' => false]) ?>
                </a>
            <?php else : ?>
                <?= $space->getProfileImage()->render(140, ['class' => 'img-profile-header-background profile-user-photo']) ?>
            <?php endif; ?>

            <div class="image-upload-loader" style="padding-top: 60px;">
                <?= $profileImageUpload->progress() ?>
            </div>

            <?= $this->render('@content/widgets/views/containerProfileImageMenu', [
                'upload' => $profileImageUpload,
                'hasImage' => $space->getProfileImage()->hasImage(),
                'deleteUrl' => $deleteUrl,
                'cropUrl' => $cropUrl,
                'dropZone' => '.profile-user-photo-container',
                'confirmBody' =>   Yii::t('SpaceModule.base', 'Do you really want to delete your profile image?')
            ])?>
        </div>
    </div>
</div>


