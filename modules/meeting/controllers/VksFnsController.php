<?php

namespace app\modules\meeting\controllers;

use app\modules\meeting\models\search\VksFnsSearch;
use app\modules\meeting\models\VksFns;

/**
 * @author toatall
 */
class VksFnsController extends BaseMeetingController
{

    /**
     * @inheritDoc
     */
    protected function createNewSearchModel()
    {
        return (new VksFnsSearch(['between_days' => 7]));
    }

    /**
     * @inheritDoc
     */
    protected function getModelClass(): string
    {
        return VksFns::class;
    }

    /**
     * @inheritDoc
     */
    protected function roleEditor()
    {
        return VksFns::roleEditor();
    }

}