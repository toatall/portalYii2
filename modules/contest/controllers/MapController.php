<?php

namespace app\modules\contest\controllers;

use app\modules\contest\models\Map;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Map controller for the `contest` module
 */
class MapController extends Controller
{

    public $layout = false;
    
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
        $missionToday = Map::findToday();
       
        if (!empty($city = Yii::$app->request->post('city')) && $missionToday != null) {
            Map::saveAnswer($city, $missionToday['id']);
        }
        
        return $this->render('index', [
            'regions' => Map::listRegions(),
            'cities' => Map::listCities(),
            'missionToday' => $missionToday,
            'missionAll' => Map::findAll(),
            'isAnswered' => Map::isAnswered(isset($missionToday['id']) ? $missionToday['id'] : null),
            'leadersWeeks' => Map::leadersWeek(),
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findMapModel($id);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => $model['place_name'] . ' (' . Yii::$app->formatter->asDate($model['date_show']) . ')',
            'content' => $this->renderAjax('view', [
                'model' => $model,
                'rightAnswers' => Map::findRightAnswers($id, $model['place_name']),
                'wrongAnswers' => Map::findWrongAnswers($id, $model['place_name']),
            ]),
        ];
    }

    private function findMapModel($id)
    {
        if (($model = Map::findById($id)) == null) {
            throw new NotFoundHttpException();
        }
        return $model;
    }    
    
}
