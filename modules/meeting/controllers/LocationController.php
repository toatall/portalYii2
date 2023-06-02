<?php

namespace app\modules\meeting\controllers;

use Yii;
use app\components\Controller;
use app\modules\meeting\models\Locations;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * @author toatall
 */
class LocationController extends Controller
{

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,                       
                        'roles' => ['admin'],
                    ],   
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['data'],
                    ],                 
                ],
            ],
        ];
    }    

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Locations::find(),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Добавление кабинета
     * 
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Locations();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Изменение кабинета
     * 
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Удаление кабинета
     * 
     * @param int $id
     * @return string
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        return 'OK';
    }

    /**
     * @param int $id
     * @throws \yii\web\NotFoundHttpException
     * @return Locations|null
     */
    private function findModel($id)
    {
        if (($model = Locations::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException();
    }

    /**
     * Locations' data
     * @return mixed
     */
    public function actionData()
    {        
        Yii::$app->response->format = Response::FORMAT_JSON;
        $query = (new Query())
            ->from(Locations::tableName())
            ->orderBy(['location' => SORT_ASC])
            ->all();
        $result = array_map(function($value) {
            return ['id' => $value['location'], 'title' => $value['location']];
        }, $query);
        
        return $result;
    }


}