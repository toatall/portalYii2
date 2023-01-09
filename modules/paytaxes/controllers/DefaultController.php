<?php

namespace app\modules\paytaxes\controllers;

use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\Response;
use app\components\Controller;
use app\modules\paytaxes\models\PayTaxesChartDay;
use app\modules\paytaxes\models\PayTaxesChartMonth;

class DefaultController extends Controller
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
                        'actions' => ['map', 'map-print', 'chart-data'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],                    
                ],
            ],
        ];
    }

    /**    
     * Главная страница (где карта)
     * @return string
     */
    public function actionMap()
    {
        $query = "
            select 
                t.code, t.name_short, g.date, g.sum1, g.sum2, g.sum3, g.sms, g.sms_1, g.sms_2, g.sms_3, 
                g.sum_left_all, g.sum_left_nifl, g.sum_left_tn, g.sum_left_zn, g.growth_sms, g.kpe_persent
            from {{%organization}} t
                outer apply (select top 1 * from {{%pay_taxes_general}} where t.code=code_org order by date desc) g
            where t.code in ('8600','8601','8602','8603','8606','8617','8619') 
                and YEAR(g.date) = :year
            order by t.sort asc
        ";
        $result = Yii::$app->db->createCommand($query, [':year'=>2022])->queryAll();

        $this->saveVisit();
        
        return $this->render('map', [
            'result' => $result,
            'raions' => $this->getRaions(),
        ]);
    }

    public function actionMapPrint()
    {
        $query = "
            select 
                t.code, t.name_short, g.date, g.sum1, g.sum2, g.sum3, g.sms, g.sms_1, g.sms_2, g.sms_3, 
                g.sum_left_all, g.sum_left_nifl, g.sum_left_tn, g.sum_left_zn, g.growth_sms, g.kpe_persent
            from {{%organization}} t
                outer apply (select top 1 * from {{%pay_taxes_general}} where t.code=code_org order by date desc) g
            where t.code in ('8600','8601','8602','8603','8606','8617','8619') 
                and YEAR(g.date) = :year
            order by t.sort asc
        ";
        $result = Yii::$app->db->createCommand($query, [':year'=>2022])->queryAll();

        return $this->renderAjax('table', [
            'result' => $result,
            'raions' => $this->getRaions(),
            'isPrint' => true,
        ]);
    }

    /**
     * сохранение информации о посетителе
     */
    private function saveVisit()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->db->createCommand()
                ->insert('{{%pay_taxes_visit}}', [
                    'ip_address' => Yii::$app->request->remoteIP,
                    'client_host' => Yii::$app->request->remoteHost,
                    'username' => Yii::$app->user->identity->username,
                    'date_create' => new Expression('getdate()'),
                ])->execute();
        }
        else {
            return $this->redirect(['/site/login']);
        }
    }

    /**
     * @return array
     */
    public function getRaions()
    {
        $raions = [
            '8600' => 'all',
            '8601' => 'hanty-mansyiskyi',
            '8602' => 'surgutskyi',
            '8603' => 'nignevartovskyi',
            '8606' => 'kondinskyi',
            '8607' => 'nignevartovskyi',
            '8610' => 'oktyaborskyi',
            '8611' => 'beloyarskyi',
            '8617' => 'surgutskyi',
            '8619' => 'nefteuganskyi',
            '8622' => 'sovetskyi',        
            '8624' => 'nignevartovskyi',
        ];
        return $raions;        
    }

    /**
     * Данные для графиков
     * @return array
     */
    public function actionChartData($org)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'months' => $this->chartDataByMonth($org),
            'days' => $this->chartDataByDay($org, 2022),
            'daysPrevYear' => $this->chartDataByDay($org, 2021),
        ];
    }

    /**
     * Данные для графика по месяцам
     * @return array
     */
    private function chartDataByMonth($org)
    {
        $currentY = date('Y');        

        $currentY = 2022;
        $previousY = $currentY-1;

        $records = PayTaxesChartMonth::find()->where([
            'year' => $currentY,
            'code_org' => $org,
        ])
        ->orderBy(new Expression("
            case 
                when [[month]] like 'С%' then 9 
                when [[month]] like 'О%' then 10 
                when [[month]] like 'Н%' then 11 
                when [[month]] like 'Д%' then 12 
                else 0 
            end
        "))
        ->all();

        $dataCurrentY = [];
        $dataPreviousY = [];
        $labels = [];

        /** @var \app\modules\paytaxes\models\PayTaxesChartMonth[] $records */
        foreach ($records as $item) {
            $labels[] = $item->month;
            $dataCurrentY[] = round($item->sum1, 2);
            $dataPreviousY[] = round($item->getValByYear($previousY), 2);            
        }       

        return [
            'labels' => $labels,
            'series' => [
                [
                    'name' => 'Всего поступлений (тыс. рублей) за ' . $currentY,
                    'data' => $dataCurrentY,
                ],
                [
                    'name' => 'Всего поступлений (тыс. рублей) за ' . $previousY,
                    'data' => $dataPreviousY,
                ],
            ],
        ];
    }

    /**
     * Данные для графика по дням
     * @return array
     */
    private function chartDataByDay($org, $year)
    {

        // $currentY = date('Y');
        // $previousY = $currentY-1;
        $currentY = $year;

        $records = PayTaxesChartDay::find()->where([
            'code_org' => $org,
            'YEAR([[date]])' => $currentY,
        ])
        ->orderBy('date asc')
        ->all();

        $dataCurrentY = [];
        // $dataPreviousY = [];
        $labels = [];

        /** @var \app\modules\paytaxes\models\PayTaxesChartDay[] $records */
        foreach ($records as $item) {
            $label = date('d.m', strtotime($item->date));
            $labels[] = $label;
            $dataCurrentY[] = round($item->sum1, 2);
            // $dataPreviousY[] = round($item->getValByYear($label . '.' . $previousY), 2);
        }
        
        return [
            'labels' => $labels,
            'series' => [
                [
                    'name' => 'Динамика поступлений (тыс. рублей) за ' . $currentY,
                    'data' => $dataCurrentY,
                ],
                // [
                //     'name' => 'Динамика поступлений (тыс. рублей) за ' . $previousY,
                //     'data' => $dataPreviousY,
                // ],
            ],
        ];        

    }    


}