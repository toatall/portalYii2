<?php

namespace app\modules\meeting\controllers;

use app\modules\meeting\models\search\VksKonturTalkSearch;
use app\modules\meeting\models\VksKonturTalk;

/**
 * @author toatall
 */
class VksKonturTalkController extends BaseMeetingController
{

    /**
     * @inheritDoc
     */
    protected function createNewSearchModel()
    {
        return (new VksKonturTalkSearch(['between_days' => 7]));
    }

    /**
     * @inheritDoc
     */
    protected function getModelClass(): string
    {
        return VksKonturTalk::class;
    }

    /**
     * @inheritDoc
     */
    protected function roleEditor()
    {
        return [VksKonturTalk::roleEditor(), VksKonturTalk::roleEditorIfns()];
    }

}