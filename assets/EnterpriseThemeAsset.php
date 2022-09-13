<?php

namespace humhub\modules\enterpriseTheme\assets;

use yii\web\View;

/**
 * Description of EnterpriseThemeAsset
 *
 * @author buddha
 */
class EnterpriseThemeAsset extends \yii\web\AssetBundle
{
    /**
     * v1.5 compatibility defer script loading
     *
     * Migrate to HumHub AssetBundle once minVersion is >=1.5
     *
     * @var bool
     */
    public $defer = true;

    /**
     * @inheritdoc
     */
    public $jsOptions = ['position' => View::POS_END];

    /**
     * @inheritdoc
     */
    public $sourcePath = '@enterprise-theme/themes/enterprise';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/humhub.enterprise.theme.js'
    ];
}
