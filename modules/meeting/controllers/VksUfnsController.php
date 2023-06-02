<?php

namespace app\modules\meeting\controllers;

use app\modules\meeting\models\search\VksUfnsSearch;
use app\modules\meeting\models\VksUfns;

/**
 * @author toatall
 */
class VksUfnsController extends BaseMeetingController
{

    /**
     * @inheritDoc
     */
    protected function createNewSearchModel()
    {
        return (new VksUfnsSearch(['between_days' => 7]));
    }

    /**
     * @inheritDoc
     */
    protected function getModelClass(): string
    {
        return VksUfns::class;
    }

    /**
     * @inheritDoc
     */
    protected function roleEditor()
    {
        return VksUfns::roleEditor();
    }

}