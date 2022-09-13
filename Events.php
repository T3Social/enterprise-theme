<?php


namespace humhub\modules\enterpriseTheme;


use humhub\modules\enterpriseTheme\models\Type;
use humhub\modules\marketplace\components\LicenceManager;
use humhub\modules\marketplace\models\Licence;
use humhub\modules\space\components\SpaceDirectoryQuery;
use humhub\modules\space\widgets\MembershipButton;
use humhub\modules\space\widgets\SpaceDirectoryFilters;
use humhub\modules\ui\view\helpers\ThemeHelper;
use Yii;
use yii\helpers\Url;

class Events
{

    public static function onAdminSpaceMenuInit($event)
    {
        $event->sender->addItem([
            'label' => Yii::t('EnterpriseThemeModule.base', 'Categories'),
            'sortOrder' => 300,
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'enterprise-theme' && Yii::$app->controller->id == 'admin'),
            'url' => Url::to(['/enterprise-theme/admin/index']),
        ]);
    }

    public static function onSpaceAdminDefaultMenuInit($event)
    {

        $event->sender->addItem([
            'label' => Yii::t('EnterpriseThemeModule.base', 'Image'),
            'sortOrder' => 290,
            'isActive' => (Yii::$app->controller->id == 'space-admin' && Yii::$app->controller->action->id == 'image'),
            'url' => $event->sender->space->createUrl('/enterprise-theme/space-admin/image'),
        ]);

        if (Type::find()->count() > 1) {
            $event->sender->addItem([
                'label' => Yii::t('EnterpriseThemeModule.base', 'Category'),
                'sortOrder' => 300,
                'isActive' => (Yii::$app->controller->id == 'space-admin' && Yii::$app->controller->action->id == 'index'),
                'url' => $event->sender->space->createUrl('/enterprise-theme/space-admin/index'),
            ]);
        }
    }

    /**
     * Add type_id to attributes
     */
    public static function onSpaceSearchAdd($event)
    {
        $event->attributes['type_id'] = $event->sender->space_type_id;
    }

    public static function onSpaceBeforeInsert($event)
    {
        $space = $event->sender;

        if ($space->space_type_id == "") {
            $type = Type::find()->orderBy(['sort_key' => SORT_ASC])->one();
            $space->space_type_id = $type->id;
        }
    }

    public static function onLicenceManagerGet($event)
    {
        if (LicenceManager::get()->type !== Licence::LICENCE_TYPE_PRO) {
            /** @var Module $module */
            $module = Yii::$app->getModule('enterprise-theme');
            $module->disableEnterpriseTheme();

            Yii::error("Disabled Enterprise Theme - No valid Professional Edition Licence found!", 'enterprise-theme');
        }
    }

    public static function onSpaceChooserCreate($event)
    {
        // Switch to Enterprise Space Chooser
        $event->config['class'] = widgets\Chooser::className();
    }

    public static function onSpaceChooserItemCreate($event)
    {
        $event->config['class'] = widgets\SpaceChooserItem::className();
    }

    public static function onInitSpaceDirectoryFilters($event)
    {
        $spaceTypes = Type::find()
            ->where(['show_in_directory' => 1])
            ->addOrderBy('sort_key')
            ->all();

        if (count($spaceTypes) < 2) {
            return;
        }

        $options = ['' => Yii::t('SpaceModule.base', 'All')];
        foreach ($spaceTypes as $spaceType) {
            /* @var $spaceType Type */
            $options[$spaceType->id] = $spaceType->title;
        }

        /* @var $spaceDirectoryFilters SpaceDirectoryFilters */
        $spaceDirectoryFilters = $event->sender;
        $spaceDirectoryFilters->addFilter('category', [
            'title' => Yii::t('EnterpriseThemeModule.base', 'Space Category'),
            'type' => 'dropdown',
            'options' => $options,
            'sortOrder' => 150,
        ]);
    }

    public static function onInitSpaceDirectoryQuery($event)
    {
        /* @var $spaceDirectoryQuery SpaceDirectoryQuery */
        $spaceDirectoryQuery = $event->sender;

        // Display only Spaces from Categories with enabled setting "Show in Directory"
        $spaceDirectoryQuery->innerJoin('space_type', 'space_type.id = space.space_type_id');
        $spaceDirectoryQuery->andWhere(['space_type.show_in_directory' => 1]);

        $category = Yii::$app->request->get('category');
        if (empty($category)) {
            return;
        }

        // Filter by requested Category
        $spaceDirectoryQuery->andWhere(['space.space_type_id' => $category]);
    }

    public static function onInitSpaceMembershipButton($event)
    {
        if (!array_key_exists('enterprise', ThemeHelper::getThemeTree(Yii::$app->view->theme))) {
            return;
        }

        /* @var $membershipButton MembershipButton */
        $membershipButton = $event->sender;

        if (!($membershipButton instanceof MembershipButton)) {
            return;
        }

        // Default options for space membership buttons
        $membershipButton->setDefaultOptions([
            'requestMembership' => ['attrs' => ['class' => 'btn btn-info btn-sm']],
            'becomeMember' => ['attrs' => ['class' => 'btn btn-info btn-sm']],
            'acceptInvite' => ['attrs' => ['class' => 'btn btn-info btn-sm'], 'togglerClass' => 'btn btn-info btn-sm'],
            'cancelPendingMembership' => ['attrs' => ['class' => 'btn btn-info btn-sm active']],
        ]);
    }

}