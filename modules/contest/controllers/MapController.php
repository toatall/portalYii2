<?php

namespace app\modules\contest\controllers;

use app\modules\contest\models\HrPeople;
use app\modules\contest\models\HrResult;
use app\modules\contest\models\HrResultData;
use app\modules\contest\models\Map;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\AccessControl;
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
        ]);
    }

    /**
     * Данные для игры
     */
    public function actionData()
    {        
        // $model = new HrResult([
        //     'username' => Yii::$app->user->identity->username,
        //     'date_create' => new Expression('getdate()'),
        // ]);
        // $model->save();

        // /** @var HrPeople[] $data */
        // $data = HrPeople::find()->all();
        // foreach ($data as $item) {
        //     (new HrResultData([
        //         'id_hr_result' => $model->id,
        //         'id_hr_people' => $item->id,
        //         'temperature' => $item->getTemp(),                
        //     ]))->save();
        // }

        // $parent = (new Query())
        //     ->from('{{%contest_hr_result_data}} t')
        //     ->leftJoin('{{%contest_hr_people}} p', 't.id_hr_people=p.id')
        //     ->select('t.id, t.id_hr_result, t.id_hr_people, t.temperature temp, p.fio, p.photo')
        //     ->where(['t.id_hr_result' => $model->id]);
        //     /*->orderBy('newid()')
        //     ->all();*/

        // $data = $parent
        //     ->orderBy('newid()')
        //     ->all();

        // $dataSort = $parent->orderBy(['fio' => SORT_ASC])->all();

        // Yii::$app->response->format = Response::FORMAT_JSON;
        // return [
        //     'data' => $data,
        //     'dataSort' => $dataSort,
        //     'idResult' => $model->id,
        // ];
    }

    /**
     * Сохранение и вывод результата
     * @return string
     */
    public function actionResult()
    {
        // $resultData = [];
        // $resultNumbers = ['right' => 0, 'wrong' => 0];
        // $users = Yii::$app->request->post('users');
        // //$idResult = Yii::$app->request->post('idResult');
        // //$modelResult = HrResult::findOne($idResult);
        // foreach ($users as $id=>$temp) {
        //     $model = HrResultData::findOne($id);
        //     $model->temperature_user = str_replace(',', '.', $temp);
        //     $model->save();
        //     if (floatval($model->temperature) == floatval($model->temperature_user)) {
        //         $resultNumbers['right'] += 1;
        //     }
        //     else {
        //         $resultNumbers['wrong'] += 1;
        //     }
        //     $resultData[$model->hrPeople->fio] = [ 
        //         'temperature' => $model->temperature,
        //         'temperature_user' => $model->temperature_user,
        //     ];
        // }
        // ksort($resultData);

        // return $this->render('result', [
        //     //'modelResult' => $modelResult,
        //     //'resultData' => $modelResult->getContestHrResultDatas()->with->orderBy(['fio' => SORT_ASC])->all(),
        //     'resultData' => $resultData,
        //     'resultNumbers' => $resultNumbers,
        // ]);
    }
    
}
