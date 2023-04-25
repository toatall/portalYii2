<?php

namespace app\modules\admin\controllers;

use app\components\Controller;
use app\modules\admin\models\FooterData;
use app\modules\admin\models\FooterType;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * Управление ссылками для футера портала
 * @author toatall
 */
class FooterDataController extends Controller
{

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['admin'],
                        ],                        
                    ],
                ],
            ]
        );
    }  
    
    /**
     * Главная страница с разделами для ссылок
     * @param int $idType
     * @return mixed
     */
    public function actionIndex($idType)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => FooterData::find()->where(['id_type' => $idType]),            
        ]);

        return $this->renderAjax('index', [
            'dataProvider' => $dataProvider,
            'idType' => $idType,
        ]);
    }

    /**
     * Добавление ссылки
     * @param int $idType идентификатор раздела
     * @return mixed
     */
    public function actionCreate($idType)
    {        
        $modelType = $this->findModelType($idType);
        $model = new FooterData([
            'id_type' => $idType,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {            
            Yii::$app->session->setFlash('success');
        }
        
        return $this->renderAjax('_form', [
            'model' => $model,
            'modelType' => $modelType,
        ]);
    }

    /**
     * Изменение ссылки
     * @param int $idType идентификатор раздела
     * @return mixed
     */
    public function actionUpdate($id)
    {        
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {            
            Yii::$app->session->setFlash('success');
        }
        
        return $this->renderAjax('_form', [
            'model' => $model,
            'modelType' => $model->type,
        ]);
    }

    /**
     * Удаление ссылки
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
     * @return FooterData
     */
    private function findModel($id)
    {
        if (($model = FooterData::findOne($id)) === null) {
            throw new NotFoundHttpException();
        }
        return $model;
    }

    /**
     * @return FooterType
     */
    private function findModelType($id)
    {
        if (($model = FooterType::findOne($id)) === null) {
            throw new NotFoundHttpException();
        }
        return $model;
    }

}