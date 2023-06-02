<?php

namespace app\modules\meeting\controllers;

use app\modules\meeting\models\Conference;
use app\modules\meeting\models\search\ConferenceSearch;

/**
 * @author toatall
 */
class ConferenceController extends BaseMeetingController
{

    /**
     * @inheritDoc
     */
    protected function createNewSearchModel()
    {
        return (new ConferenceSearch(['between_days' => 7]));
    }

    /**
     * @inheritDoc
     */
    protected function getModelClass(): string
    {
        return Conference::class;
    }

    /**
     * @inheritDoc
     */
    protected function roleEditor(): string
    {
        return Conference::roleEditor();
    }

}