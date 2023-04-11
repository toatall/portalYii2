<?php

namespace app\modules\contest\modules\space\controllers;


use app\components\Controller;
use app\modules\contest\modules\space\models\Space;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{

    public $layout = 'main';
    
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
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $models = Space::find()->all();
        return $this->render('index', [
            'models' => $models,
        ]);
    }

    public function actionLike($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->loadModel($id);
        $model->like();
        return [
            'is_like' => $model->likeModel !== null,
            'count' => $model->countLike(),
        ];
    }

    private function loadModel($id)
    {
        if (($model = Space::findOne($id)) === null) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
    
}
