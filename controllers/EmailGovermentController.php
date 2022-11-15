<?php

namespace app\controllers;

use app\models\zg\EmailGovermentSearch;
use Yii;
use yii\filters\AccessControl;
use app\components\Controller;
use app\models\zg\EmailGoverment;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class EmailGovermentController
 * @package app\controllers
 */
class EmailGovermentController extends Controller
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
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin', EmailGoverment::roleModerator()],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $searchModel = new EmailGovermentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     */
    public function actionCreate()
    {
        $model = new EmailGoverment();

        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {            
            return ['content' => 'OK'];
        }
        return [
            'title' => 'Создание адреса',
            'content' => $this->renderAjax('form', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * @return string
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {            
            return ['content' => 'OK'];
        }
        return [
            'title' => 'Изменение адреса ' . $model->org_name,
            'content' => $this->renderAjax('form', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Deletes an existing EmailGoverment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();       
        return $this->redirect(['index']);
    }


    /**
     * Finds the Department model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EmailGoverment the loaded model
     */
    protected function findModel($id)
    {
        if (($model = EmailGoverment::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }


}
