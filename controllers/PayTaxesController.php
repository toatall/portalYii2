<?php

namespace app\controllers;

use app\models\page\Page;
use Exception;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class PayTaxesController extends \yii\web\Controller
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
     * Главная страница (где карта)
     * @return string
     */
    public function actionMap()
    {
        $query = "
            select 
                t.code, t.name_short, g.date, g.sum1, g.sum2, g.sum3, g.sms, g.sms_1, g.sms_2, g.sms_3, 
                g.sum_left_all, g.sum_left_nifl, g.sum_left_tn, g.sum_left_zn
            from {{%organization}} t
                outer apply (select top 1 * from {{%pay_taxes_general}} where t.code=code_org order by date desc) g
            where t.code in ('8600','8601','8602','8603','8606','8617','8619') 
            order by t.sort asc
        ";
        $result = Yii::$app->db->createCommand($query)->queryAll();

        $this->saveVisit();
        
        return $this->render('map', [
            'result' => $result,
            'raions' => $this->getRaions(),
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
        //return isset($raions[$code]) ? $raions[$code] : null;
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
            'days' => $this->chartDataByDay($org),
        ];
    }

    /**
     * Данные для графика по месяцам
     * @return array
     */
    private function chartDataByMonth($org)
    {
        // по месяцам
        $queryResult = (new Query())
            ->from('{{%pay_taxes_chart_month}}')
            ->where([
                'code_org' => $org,
            ])
            ->all();

        $result = ['labels' => [], 'datasets' => [
            [
                'label' => 'Всего поступлений (тыс. рублей)',
                'backgroundColor' => '#aed6f1',
                'data' => [],
            ],
        ]];

        foreach ($queryResult as $item) {
            $result['labels'][] = $item['month'];
            $result['datasets'][0]['data'][] = round($item['sum1'], 2);
        }

        return $result;
    }

    /**
     * Данные для графика по дням
     * @return array
     */
    private function chartDataByDay($org)
    {
         // по месяцам
         $queryResult = (new Query())
         ->from('{{%pay_taxes_chart_day}}')
         ->where([
             'code_org' => $org,
         ])
         ->all();

     $result = ['labels' => [], 'datasets' => [
         [
             'label' => 'Динамика поступлений (тыс. рублей)',
             'backgroundColor' => '#4760d2',
             'data' => [],
         ],
     ]];

     foreach ($queryResult as $item) {
         $result['labels'][] = Yii::$app->formatter->asDate($item['date']);
         $result['datasets'][0]['data'][] = round($item['sum1'], 2);
     }

     return $result;

    }


}