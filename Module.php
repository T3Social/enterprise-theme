<?php

namespace humhub\modules\enterpriseTheme;

use humhub\libs\DynamicConfig;
use humhub\modules\enterpriseTheme\models\SpaceType;
use humhub\modules\enterpriseTheme\models\Type;
use humhub\modules\ui\view\helpers\ThemeHelper;
use Yii;

class Module extends \humhub\components\Module
{
    /**
     * @inheritdoc
     */
    public $resourcesPath = 'resources';

    /**
     * @var int amount of visible spaces per space type
     */
    public $maxVisibleSpaces = 8;

    /**
     * @inheritdoc
     */
    public function disable()
    {
        $this->disableEnterpriseTheme();
        parent::disable();
    }

    public function enable()
    {
        if (parent::enable()) {
            $this->enableEnterpriseTheme();
            return true;
        }
        return false;
    }


    /**
     * Enables the Enterprise Theme
     */
    private function enableEnterpriseTheme()
    {
        // Already a theme based on Enterprise theme is active
        foreach (ThemeHelper::getThemeTree(Yii::$app->view->theme) as $theme) {
            if ($theme->name === 'enterprise') {
                return;
            }
        }

        $theme = ThemeHelper::getThemeByName('enterprise');
        if ($theme !== null) {
            $theme->activate();
            DynamicConfig::rewrite();
        }
    }

    /**
     * Disables the Enterprise Theme or other active themes based on the Enterprise theme
     */
    public function disableEnterpriseTheme()
    {
        foreach (ThemeHelper::getThemeTree(Yii::$app->view->theme) as $theme) {
            if ($theme->name === 'enterprise') {
                $ceTheme = ThemeHelper::getThemeByName('HumHub');
                $ceTheme->activate();
                break;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getPermissions($contentContainer = null)
    {
        if ($contentContainer === null) {
            $permissions = [];

            // Return SpaceType 
            foreach (Type::find()->all() as $spaceType) {
                $permissions[] = new permissions\CreateSpaceType(['spaceType' => $spaceType]);
            }

            return $permissions;
        }

        return [];
    }


}