<?php

namespace app\modules\contest\controllers;

use app\modules\contest\models\HrPeople;
use app\modules\contest\models\HrResult;
use app\modules\contest\models\HrResultData;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','data','result'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['error'],
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }
    
    
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    
    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Данные для игры
     */
    public function actionData()
    {        
        $model = new HrResult([
            'username' => Yii::$app->user->identity->username,
            'date_create' => new Expression('getdate()'),
        ]);
        $model->save();

        /** @var HrPeople[] $data */
        $data = HrPeople::find()->all();
        foreach ($data as $item) {
            (new HrResultData([
                'id_hr_result' => $model->id,
                'id_hr_people' => $item->id,
                'temperature' => $item->getTemp(),                
            ]))->save();
        }

        $parent = (new Query())
            ->from('{{%contest_hr_result_data}} t')
            ->leftJoin('{{%contest_hr_people}} p', 't.id_hr_people=p.id')
            ->select('t.id, t.id_hr_result, t.id_hr_people, t.temperature temp, p.fio, p.photo')
            ->where(['t.id_hr_result' => $model->id]);
            /*->orderBy('newid()')
            ->all();*/

        $data = $parent
            ->orderBy('newid()')
            ->all();

        $dataSort = $parent->orderBy(['fio' => SORT_ASC])->all();

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'data' => $data,
            'dataSort' => $dataSort,
            'idResult' => $model->id,
        ];
    }

    /**
     * Сохранение и вывод результата
     * @return string
     */
    public function actionResult()
    {
        $resultData = [];
        $resultNumbers = ['right' => 0, 'wrong' => 0];
        $users = Yii::$app->request->post('users');
        //$idResult = Yii::$app->request->post('idResult');
        //$modelResult = HrResult::findOne($idResult);
        foreach ($users as $id=>$temp) {
            $model = HrResultData::findOne($id);
            $model->temperature_user = str_replace(',', '.', $temp);
            $model->save();
            if (floatval($model->temperature) == floatval($model->temperature_user)) {
                $resultNumbers['right'] += 1;
            }
            else {
                $resultNumbers['wrong'] += 1;
            }
            $resultData[$model->hrPeople->fio] = [ 
                'temperature' => $model->temperature,
                'temperature_user' => $model->temperature_user,
            ];
        }
        ksort($resultData);

        return $this->render('result', [
            //'modelResult' => $modelResult,
            //'resultData' => $modelResult->getContestHrResultDatas()->with->orderBy(['fio' => SORT_ASC])->all(),
            'resultData' => $resultData,
            'resultNumbers' => $resultNumbers,
        ]);
    }
    
}
