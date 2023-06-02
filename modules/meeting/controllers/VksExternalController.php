<?php

namespace app\modules\meeting\controllers;

use app\modules\meeting\models\search\VksExternalSearch;
use app\modules\meeting\models\VksExternal;
use app\modules\meeting\models\VksExternalExtension;
use Yii;

/**
 * @author toatall
 */
class VksExternalController extends BaseMeetingController
{

    /**
     * @inheritDoc
     */
    protected function createNewSearchModel()
    {
        return (new VksExternalSearch(['between_days' => 7]));
    }

    /**
     * @inheritDoc
     */
    protected function getModelClass(): string
    {
        return VksExternal::class;
    }

    /**
     * @inheritDoc
     */
    protected function roleEditor(): string
    {
        return VksExternal::roleEditor();
    }

    /**
     * Создание мероприятия
     * 
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VksExternal();
        $modelExtension = new VksExternalExtension();

        if ($model->load(Yii::$app->request->post()) && $modelExtension->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $modelExtension->id_meeting = $model->id;
                if ($modelExtension->save()) {
                    return $this->redirect('index');
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelExtension' => $modelExtension,
        ]);
    }

    /**
     * Редактирование мероприятия
     * 
     * @param int $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /** @var VksExternal $model */
        $model = $this->findModel($id);
        $modelExtension = $model->extension ?? new VksExternalExtension([
            'id_meeting' => $model->id,
        ]);
        
        if ($model->load(Yii::$app->request->post()) && $modelExtension->load(Yii::$app->request->post())) {
            if ($model->save() && $modelExtension->save()) {
                return $this->redirect('index');
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelExtension' => $modelExtension,
        ]);
    }
    

}