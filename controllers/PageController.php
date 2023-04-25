<?php

namespace app\controllers;

use app\models\page\Page;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use app\components\Controller;
use app\models\page\PageSearch;

class PageController extends Controller
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
    public function actionIndex($tag=null)
    {
        $this->getView()->title = 'Новости';
        $searchModel = new PageSearch();                

        if ($tag!=null) {
            $searchModel->tags = $tag;
        }

        $dataProvider = $searchModel->searchPublic(\Yii::$app->request->queryParams);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('/news/index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->render('/news/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Просмотр новости
     * @param $id int
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax) {
            $resultJson = [
                'title'=>$model->title,
                'content' => $this->renderAjax('/news/view', ['model'=>$model]),
            ];
            return Json::encode($resultJson);
        }

        return $this->render('/news/view', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return Page|array|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Page::publicFindOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
