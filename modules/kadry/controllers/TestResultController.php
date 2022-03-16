<?php

namespace app\modules\kadry\controllers;

use app\modules\kadry\models\Award;
use app\modules\kadry\models\TestResult;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

class TestResultController extends Controller
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
                        'roles' => ['@',],
                    ],                
                ],
            ],           
        ];
    }
    

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = (new Query())
            ->from('{{%kadry_test_result}}')
            ->select('period, period_year')
            ->groupBy('period, period_year')
            ->all();        

        $periods = ArrayHelper::map($query, 'period', function($value) { return TestResult::periodNameByCode($value['period']); }, 'period_year');
        
        return $this->render('index', [
            'periodsAll' => $periods,
        ]);
    }    

    public function actionView($year, $period)
    {
        $query = (new Query())
            ->from('{{%kadry_test_result}} t')
            ->leftJoin('{{%organization}} org', 'org.code = t.org_code')
            ->select('t.*, org.name')
            ->where([
                't.period_year' => $year,
                't.period' => $period,
            ])
            ->orderBy(['org.name' => SORT_ASC])
            ->all();

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => 'За ' . TestResult::periodNameByCode($period) . ' ' . $year,
            'content' => $this->renderAjax('view', [
            'query' => $query,
            ]),
        ];
    }

}
