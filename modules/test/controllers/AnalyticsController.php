<?php

namespace app\modules\test\controllers;

use yii\filters\AccessControl;
use app\components\Controller;
use Yii;
use yii\helpers\Url;
use yii\web\Response;

/**
 * Analytics controller for the `test` module
 */
class AnalyticsController extends Controller
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


    public function actionIndex()
    {
        return $this->render('index');
    }  

    public function actionChartRightTotal($date1, $date2)
    {
        $sql = "
            SELECT 
                 t.org_code
                ,count(DISTINCT t.id) AS count_test
                ,count(DISTINCT CASE WHEN q.is_right = 1 THEN q.id ELSE NULL END) count_right
                ,count(DISTINCT CASE WHEN q.is_right = 0 THEN q.id ELSE NULL END) count_wrong
            FROM {{%test_result}} t
                LEFT JOIN {{%test_result_question}} q ON q.id_test_result = t.id       
                INNER JOIN {{%test_question}} q_real ON q.id_test_question = q_real.id   
            WHERE t.status=1 AND t.date_create BETWEEN :date1 AND :date2
                -- AND q_real.type_question <= 1
            GROUP BY t.org_code
            ORDER BY t.org_code
        ";
        $query = Yii::$app->db->createCommand($sql, [
            ':date1' => $date1,
            ':date2' => $date2,
        ])->queryAll();

        // $dataRight = [];
        // $dataWrong = [];
        // $categories = [];
        // $total = [];
        $res = [];
        foreach($query as $item) {
            // $total[] = $item['count_test'];
            // $categories[] = $item['org_code'];
            // $dataRight[] = $item['count_right'];
            // $dataWrong[] = $item['count_wrong'];
            $res[] = [
                'total' => $item['count_test'],
                'category' => $item['org_code'],
                'right' => $item['count_right'],
                'wrong' => $item['count_wrong'],
                'urlDetail' => Url::to(['/test/analytics/chart-count-by-date', 'org'=>$item['org_code'], 'date1'=>$date1, 'date2'=>$date2]),
            ];
        }
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            // 'data' => [
            //     [
            //         'name' => 'Правильные ответы',
            //         'data' => $dataRight,
            //     ],
            //     [
            //         'name' => 'Неправильные ответы',
            //         'data' => $dataWrong,
            //     ],            
            // ],
            // 'dataRight' => $dataRight,
            // 'dataWrong' => $dataWrong,
            // 'categories' => $categories,
            // 'total' => $total,
            'res' => $res,
        ];
    }

    public function actionChartCountByDate($org, $date1, $date2)
    {
        $sql = "
            SELECT 
                 convert(varchar, t.date_create, 104) [date]
                ,count(DISTINCT t.id) AS count_test
                ,count(DISTINCT CASE WHEN q.is_right = 1 THEN q.id ELSE NULL END) count_right
                ,count(DISTINCT CASE WHEN q.is_right = 0 THEN q.id ELSE NULL END) count_wrong
            FROM {{%test_result}} t
                LEFT JOIN {{%test_result_question}} q ON q.id_test_result = t.id       
                INNER JOIN {{%test_question}} q_real ON q.id_test_question = q_real.id   
            WHERE t.status=1 AND t.date_create BETWEEN :date1 AND :date2
                AND t.org_code = :org                
            GROUP BY convert(varchar, t.date_create, 104), convert(varchar, t.date_create, 112)
            ORDER BY convert(varchar, t.date_create, 112)
        ";
        $query = Yii::$app->db->createCommand($sql, [
            ':org' => $org,
            ':date1' => $date1,
            ':date2' => $date2,
        ])->queryAll();

        // $dataRight = [];
        // $dataWrong = [];
        // $categories = [];
        // $total = [];
        $res = [];
        foreach($query as $item) {
            // $total[] = $item['count_test'];
            // $categories[] = $item['org_code'];
            // $dataRight[] = $item['count_right'];
            // $dataWrong[] = $item['count_wrong'];
            $res[] = [
                'date' => $item['date'],
                'right' => $item['count_right'],
                'wrong' => $item['count_wrong'],
            ];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            // 'data' => [
            //     [
            //         'name' => 'Правильные ответы',
            //         'data' => $dataRight,
            //     ],
            //     [
            //         'name' => 'Неправильные ответы',
            //         'data' => $dataWrong,
            //     ],            
            // ],
            // 'categories' => $categories,
            // 'total' => $total,
            'res' => $res,
        ];
    }
    
    
}
