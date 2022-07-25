<?php

namespace app\modules\restricteddocs\controllers;

use Yii;
use app\modules\restricteddocs\models\RestrictedDocs;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * DocsController implements the CRUD actions for RestrictedDocs model.
 */
class DocsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,                      
                        'roles' => ['admin', RestrictedDocs::roleModerator()],
                    ],                    
                ],
            ],
        ];
    }
   
    /**
     * Displays a single RestrictedDocs model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RestrictedDocs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RestrictedDocs();
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->uploadFiles = UploadedFile::getInstances($model, 'uploadFiles');
            $model->upload();
            return ['content' => 'OK'];
        }

        
        return [
            'title' => 'Добавление документа',
            'content' => $this->renderAjax('_form', [
                'model' => $model,
            ]),
        ];        
    }

    /**
     * Updates an existing RestrictedDocs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->uploadFiles = UploadedFile::getInstances($model, 'uploadFiles');
            $model->upload();
            return ['content' => 'OK'];
        }

        return [
            'title' => 'Редактирование документа: ' . $model->name,
            'content' => $this->renderAjax('_form', [
                'model' => $model,
            ]),
        ];   
    }

    /**
     * Deletes an existing RestrictedDocs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return 'OK';        
    }

    /**
     * Finds the RestrictedDocs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RestrictedDocs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RestrictedDocs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
