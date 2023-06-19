<?php

namespace app\controllers;

use app\models\news\News;
use Yii;
use yii\filters\AccessControl;
use app\models\news\NewsSearch;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use app\components\Controller;

class NewsController extends Controller
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
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Новости
     * @return string
     */
    public function actionIndex($organization=null, $section=null, $tag=null)
    {
        $this->getView()->title = 'Новости';
        $searchModel = new NewsSearch();        

        if ($organization!=null) {
            $searchModel->id_organization = $organization;
        }

        if ($section!=null) {
            $searchModel->searchSection = $section;
        }

        if ($tag!=null) {
            $searchModel->tags = $tag;
        }

        $dataProvider = $searchModel->searchPublic(\Yii::$app->request->queryParams);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Просмотр новости
     * @param $id int
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        // $this->saveVisit($id);

        if (Yii::$app->request->isAjax) {
            $resultJson = [
                'title'=>$model->title,
                'content' => $this->renderAjax('view', ['model'=>$model]),
            ];
            return Json::encode($resultJson);
        }


        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Сохранить посещение
     * @param $id
     * @throws \yii\db\Exception
     */
    // protected function saveVisit($id)
    // {
    //     /*
    //     $query = (new Query())
    //         ->from('{{%news_visit}}')
    //         ->where([
    //             'id_news' => $id,
    //             'username' => Yii::$app->user->identity->username,
    //         ]);

    //     if (!$query->exists()) {
    //     */
    //         Yii::$app->db->createCommand()
    //             ->insert('{{%news_visit}}', [
    //                 'id_news' => $id,
    //                 'username' => Yii::$app->user->identity->username,
    //                 'ip_address' => $_SERVER['REMOTE_ADDR'],
    //                 'hostname' => $_SERVER['REMOTE_HOST'],
    //             ])
    //             ->execute();
    //     //}
    // }

    /**
     * @param $id
     * @return News|array|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = News::publicFindOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Лента новостей
     * @return string
     */
    public function actionGeneral()
    {
        $this->getView()->title = 'Новости';
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->searchPublic(\Yii::$app->request->queryParams, true);
              
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('indexGeneral', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else {
            return $this->render('indexGeneral', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionGeneralNew()
    {
        $this->getView()->title = 'Новости';
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->searchPublic(\Yii::$app->request->queryParams, true, 5);
              
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('indexGeneralNew', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else {
            return $this->render('indexGeneralNew', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Лента новостей УФНС
     * @return mixed
     */
    public function actionUfns()
    {
        $this->getView()->title = 'Новости УФНС';
        $searchModel = new NewsSearch();
        $searchModel->on_general_page = 1;
        $dataProvider = $searchModel->searchUfns(\Yii::$app->request->queryParams);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('indexUfns', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else {
            return $this->render('indexUfns', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Лента новостей УФНС
     * @return mixed
     */
    public function actionIfns()
    {
        $this->getView()->title = 'Новости ИФНС';
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->searchIfns(\Yii::$app->request->queryParams);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('indexIfns', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else {
            return $this->render('indexIfns', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }    

}
