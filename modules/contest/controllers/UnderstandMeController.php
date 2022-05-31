<?php

namespace app\modules\contest\controllers;

use app\modules\contest\models\UnderstandMe;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * UnderstandMe controller for the `contest` module
 */
class UnderstandMeController extends Controller
{

    public $layout = 'understand_me';
    
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
     * Главная страница
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'data' => UnderstandMe::getData(),
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findById($id);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => $model->title,
            'content' => $this->renderAjax('view', [
                'model' => UnderstandMe::getItemById($id),
            ]),
        ];
    }

    private function findById($id)
    {
        if (($model = UnderstandMe::getItemById($id)) == null) {
            throw new NotFoundHttpException('Page not found');
        }
        return $model;
    }

    
    
}
