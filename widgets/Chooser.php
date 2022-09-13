<?php

namespace humhub\modules\enterpriseTheme\widgets;

use humhub\modules\enterpriseTheme\models\SpaceType;
use humhub\modules\enterpriseTheme\models\Type;
use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;
use humhub\modules\space\permissions\SpaceDirectoryAccess;
use humhub\modules\space\widgets\Chooser as FatherChooser;
use humhub\modules\ui\view\helpers\ThemeHelper;
use Yii;

/**
 * Class Chooser
 * @package humhub\modules\enterpriseTheme\widgets
 */
class Chooser extends FatherChooser
{
    /**
     * @var Type[] | null
     */
    private $spaceTypes = [];

    /**
     * @var string
     */
    public $viewName = '@enterprise-theme/widgets/views/spaceChooser';

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->spaceTypes = Type::find()->orderBy(['sort_key' => SORT_ASC])->all();

        if (ThemeHelper::isFluid()) {
            $this->lazyLoad = false;
        }

        parent::configure();
    }

    /**
     * @return array
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     */
    protected function getViewParams()
    {
        $memberships = $followSpaces = $typeMembershipMap = [];

        if (!$this->lazyLoad) {
            $memberships = $this->getMemberships();
            $followSpaces = $this->getFollowSpaces();
            $typeMembershipMap = $this->getMembershipMap($memberships, $followSpaces);
        }

        return [
            'currentSpace' => $this->getCurrentSpace(),
            'spaceTypes' => $this->spaceTypes,
            'noSpaceHtml' => $this->getNoSpaceHtml(),
            'createSpaceTypes' => $this->getCreateSpaceTypes(),
            'memberships' => $memberships,
            'followSpaces' => $followSpaces,
            'typeMembershipMap' => $typeMembershipMap,
            'canAccessDirectory' => Yii::$app->user->can(SpaceDirectoryAccess::class),
        ];
    }

    /**
     * @return Type[]
     * @throws \yii\base\InvalidConfigException
     */
    public function getCreateSpaceTypes()
    {
        if (!$this->canCreateSpace()) {
            return [];
        }

        return array_filter($this->spaceTypes, function ($type) {
            /** @var $type SpaceType */
            return $type->canCreateSpace();
        });
    }

    /**
     * @param $memberships Membership[]
     * @param $followSpaces Space[]
     * @param array $map
     * @return array|mixed
     */
    private function getMembershipMap($memberships, $followSpaces, $map = [])
    {
        foreach ($this->spaceTypes as $spaceType) {
            $item = [
                'spaceType' => $spaceType,
                'memberships' => [], 'visibleMemberships' => [], 'hiddenMemberships' => [],
                'following' => [], 'visibleFollowing' => [], 'hiddenFollowing' => [],
            ];

            foreach ($memberships as $membership) {
                if ($membership->space->space_type_id == $spaceType->id) {
                    $item['memberships'][] = $membership;
                }
            }

            foreach ($followSpaces as $followSpace) {
                if ($followSpace->space_type_id == $spaceType->id) {
                    $item['following'][] = $followSpace;
                }
            }

            $map[$spaceType->id] = $this->prepareVisibility($item);
        }

        return $map;
    }

    /**
     * @param $item
     */
    private function prepareVisibility($item)
    {
        $maxVisibleSpaces = Yii::$app->getModule('enterprise-theme')->maxVisibleSpaces;

        $visibleAll = (count($item['memberships']) + count($item['following'])) <= $maxVisibleSpaces;
        $partiallyMembership = count($item['memberships']) >= $maxVisibleSpaces;

        if ($visibleAll) {
            $item['visibleMemberships'] = $item['memberships'];
            $item['visibleFollowing'] = $item['following'];
        } elseif ($partiallyMembership) {
            $item['visibleMemberships'] = array_slice($item['memberships'], 0, $maxVisibleSpaces);
            $item['hiddenMemberships'] = array_slice($item['memberships'], $maxVisibleSpaces);
            $item['hiddenFollowing'] = $item['following'];
        } else {
            $visibleFollowingCount = $maxVisibleSpaces - count($item['memberships']);
            $item['visibleMemberships'] = $item['memberships'];
            $item['visibleFollowing'] = array_slice($item['following'], 0, $visibleFollowingCount);
            $item['hiddenFollowing'] = array_slice($item['following'], $visibleFollowingCount);
        }

        return $item;
    }
}
