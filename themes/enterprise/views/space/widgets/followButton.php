<?php

use yii\helpers\Html;

if ($space->isFollowedByUser()) {
    print Html::a(Yii::t('SpaceModule.base', "Unfollow"), $space->createUrl('/space/space/unfollow'), array('data-method' => 'POST', 'class' => 'btn btn-primary btn-sm'));
} else {
    print Html::a(Yii::t('SpaceModule.base', "Follow"), $space->createUrl('/space/space/follow'), array('data-method' => 'POST', 'class' => 'btn btn-primary btn-sm'));
}
