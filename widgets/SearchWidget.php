<?php

namespace humhub\modules\enterpriseTheme\widgets;

use humhub\modules\user\helpers\AuthHelper;
use Yii;
use humhub\components\Widget;

/**
 * SearchWidget display the search in the enterprise theme
 *
 * @author Luke
 */
class SearchWidget extends Widget
{

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (Yii::$app->user->isGuest && !AuthHelper::isGuestAccessEnabled()) {
            return;
        }

        return $this->render('search');
    }

}