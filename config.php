<?php

use humhub\modules\enterpriseTheme\Events;
use humhub\modules\space\components\SpaceDirectoryQuery;
use humhub\modules\space\models\Space;
use humhub\components\Widget;
use humhub\modules\space\widgets\MembershipButton;
use humhub\modules\space\widgets\SpaceDirectoryFilters;
use humhub\modules\ui\menu\widgets\Menu;

/** @noinspection MissedFieldInspection */
return [
    'id' => 'enterprise-theme',
    'class' => 'humhub\modules\enterpriseTheme\Module',
    'namespace' => 'humhub\modules\enterpriseTheme',
    'events' => [
        ['humhub\modules\admin\widgets\SpaceMenu', Menu::EVENT_INIT, ['humhub\modules\enterpriseTheme\Events', 'onAdminSpaceMenuInit']],
        ['humhub\modules\space\models\Space', Space::EVENT_SEARCH_ADD, ['humhub\modules\enterpriseTheme\Events', 'onSpaceSearchAdd']],
        ['humhub\modules\space\modules\manage\widgets\DefaultMenu', Menu::EVENT_INIT, ['humhub\modules\enterpriseTheme\Events', 'onSpaceAdminDefaultMenuInit']],
        ['humhub\modules\space\models\Space', Space::EVENT_BEFORE_INSERT, ['humhub\modules\enterpriseTheme\Events', 'onSpaceBeforeInsert']],
        ['humhub\modules\space\widgets\Chooser', Widget::EVENT_CREATE, ['humhub\modules\enterpriseTheme\Events', 'onSpaceChooserCreate']],
        ['humhub\modules\space\widgets\SpaceChooserItem', Widget::EVENT_CREATE, ['humhub\modules\enterpriseTheme\Events', 'onSpaceChooserItemCreate']],
        ['humhub\modules\marketplace\components\LicenceManager', 'getLicence', ['humhub\modules\enterpriseTheme\Events', 'onLicenceManagerGet']],
        [SpaceDirectoryFilters::class, SpaceDirectoryFilters::EVENT_INIT, [Events::class, 'onInitSpaceDirectoryFilters']],
        [SpaceDirectoryQuery::class, SpaceDirectoryQuery::EVENT_INIT, [Events::class, 'onInitSpaceDirectoryQuery']],
        [MembershipButton::class, MembershipButton::EVENT_INIT, [Events::class, 'onInitSpaceMembershipButton']],
    ]
];
?>