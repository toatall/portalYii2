<?php

namespace app\controllers;

use app\helpers\DateHelper;
use app\models\Organization;
use app\models\page\Page;
use app\models\regecr\RegEcrSearch;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\Controller;

class RegecrController extends Controller
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
     * @param null $date1
     * @param null $date2
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex($date1=null, $date2=null)
    {
        if ($date1 == null) {
            $date1 = date_add(new \DateTime('now'), date_interval_create_from_date_string('-1 month'));
            $date1 = Yii::$app->formatter->asDate($date1);
        }
        if ($date2 == null) {
            $date2 = DateHelper::today();
        }

        $query = new Query();
        $resultQuery = $query->from('{{%reg_ecr}}')
            ->select('code_org, sum(count_create) count_create, sum(count_vote) count_vote, avg(avg_eval_a_1_1) avg_eval_a_1_1, '
                           . 'avg(avg_eval_a_1_2) avg_eval_a_1_2, avg(avg_eval_a_1_3) avg_eval_a_1_3')
            ->where(['>=', 'date_reg', $date1])
            ->andWhere(['<=', 'date_reg', $date2])
            ->andWhere(['date_delete' => null])
            ->groupBy('code_org')
            ->all();

        $sum = [
            'count_create' => 0,
            'count_vote' => 0,
            'avg_eval_a_1_1' => 0,
            'avg_eval_a_1_2' => 0,
            'avg_eval_a_1_3' => 0,
        ];

        foreach ($resultQuery as $q) {
            $sum['count_create'] += $q['count_create'];
            $sum['count_vote'] += $q['count_vote'];
            $sum['avg_eval_a_1_1'] += $q['avg_eval_a_1_1'];
            $sum['avg_eval_a_1_2'] += $q['avg_eval_a_1_2'];
            $sum['avg_eval_a_1_3'] += $q['avg_eval_a_1_3'];
        }

        $sum['avg_eval_a_1_1'] = ($sum['avg_eval_a_1_1']) ? ($sum['avg_eval_a_1_1'] / count($resultQuery)) : $sum['avg_eval_a_1_1'];
        $sum['avg_eval_a_1_2'] = ($sum['avg_eval_a_1_2']) ? ($sum['avg_eval_a_1_2'] / count($resultQuery)) : $sum['avg_eval_a_1_2'];
        $sum['avg_eval_a_1_3'] = ($sum['avg_eval_a_1_3']) ? ($sum['avg_eval_a_1_3'] / count($resultQuery)) : $sum['avg_eval_a_1_3'];

        return $this->render('index', [
            'resultQuery' => $resultQuery,
            'sum' => $sum,
            'date1' => $date1,
            'date2' => $date2,
        ]);
    }


    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
   public function actionDetail()
   {
        $searchModel = new RegEcrSearch();
        $date1 = date_add(new \DateTime('now'), date_interval_create_from_date_string('-1 month'));
        $date1 = Yii::$app->formatter->asDate($date1);
        $searchModel->searchDate1 = $date1;
        $searchModel->searchDate2 = DateHelper::today();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('detail', [
           'searchModel' => $searchModel,
           'dataProvider' => $dataProvider,
       ]);
   }

    /**
     * @param string|null $date1
     * @param string|null $date2
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
   public function actionChart($date1=null, $date2=null)
   {
       if ($date1 == null) {
           $date1 = date_add(new \DateTime('now'), date_interval_create_from_date_string('-1 month'));
           $date1 = Yii::$app->formatter->asDate($date1);
       }
       if ($date2 == null) {
           $date2 = DateHelper::today();
       }

       $ifns = Organization::getDropDownList();

       return $this->render('chart', [
           'ifns' => $ifns,
           'date1' => $date1,
           'date2' => $date2,
       ]);
   }

    /**
     * @param $code
     * @param $date1
     * @param $date2
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
   public function actionChartAjax($code, $date1, $date2)
   {
       if ($date1 == null) {
           $date1 = date_add(new \DateTime('now'), date_interval_create_from_date_string('-1 month'));
           $date1 = Yii::$app->formatter->asDate($date1);
       }
       if ($date2 == null) {
           $date2 = DateHelper::today();
       }

       $modelOrganization = $this->findModelOrganization($code);
       $result = $this->loadChartData($code, $date1, $date2);
       $colors = $this->getParamColors();

       Yii::$app->response->format = Response::FORMAT_JSON;
       return [
           'orgName' => $modelOrganization->getFullName(),
           'data' => [
               'labels' => $result['labels'],
               'datasets' => [
                   [
                       'label' => 'Количество вновь созданных ООО',
                       'data' => $result['count_create'],
                       'borderColor' => $colors['chart_count_create'],
                       'backgroundColor' => $colors['chart_count_create'],
                       'fill' => false,
                   ],
                   [
                       'label' => 'Кол-во опрошенных',
                       'data' => $result['count_vote'],
                       'borderColor' => $colors['chart_count_vote'],
                       'backgroundColor' => $colors['chart_count_vote'],
                       'fill' => false,
                   ],
                   [
                       'label' => 'Средняя оценка А 1.1',
                       'data' => $result['avg_eval_a_1_1'],
                       'borderColor' => $colors['chart_avg_eval_a_1_1'],
                       'backgroundColor' => $colors['chart_avg_eval_a_1_1'],
                       'fill' => false,
                   ],
                   [
                       'label' => 'Средняя оценка А 1.2',
                       'data' => $result['avg_eval_a_1_2'],
                       'borderColor' => $colors['chart_avg_eval_a_1_2'],
                       'backgroundColor' => $colors['chart_avg_eval_a_1_3'],
                       'fill' => false,
                   ],
                   [
                       'label' => 'Средняя оценка А 1.3',
                       'data' => $result['avg_eval_a_1_3'],
                       'borderColor' => $colors['chart_avg_eval_a_1_3'],
                       'backgroundColor' => $colors['chart_avg_eval_a_1_3'],
                       'fill' => false,
                   ],
               ],
           ],
       ];
   }

    /**
     * @return array
     */
   private function getParamColors()
   {
       return Yii::$app->params['regecr']['colors'];
   }

    /**
     * @param $code_org
     * @param $date1
     * @param $date2
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    private function loadChartData($code_org, $date1, $date2)
    {
        $query = new Query();

        if ($code_org == '8600') {
            $resultQuery = $query
                ->from('{{%reg_ecr}}')
                ->select('date_reg, sum(count_create) count_create, sum(count_vote) count_vote, '
                    . 'avg(avg_eval_a_1_1) avg_eval_a_1_1, avg(avg_eval_a_1_2) avg_eval_a_1_2, avg(avg_eval_a_1_3) avg_eval_a_1_3')
                ->where([
                    'date_delete' => null,
                ])
                ->andFilterWhere(['>=', 'date_reg', $date1])
                ->andFilterWhere(['<=', 'date_reg', $date2])
                ->groupBy('date_reg')
                ->all();
        }
        else {
            $resultQuery = $query
                ->from('{{%reg_ecr}}')
                ->where([
                    'date_delete' => null,
                    'code_org' => $code_org,
                ])
                ->andFilterWhere(['>=', 'date_reg', $date1])
                ->andFilterWhere(['<=', 'date_reg', $date2])
                ->all();
        }

        $result = [];
        $result['labels'] = [];
        $result['count_create'] = [];
        $result['count_vote'] = [];
        $result['avg_eval_a_1_1'] = [];
        $result['avg_eval_a_1_2'] = [];
        $result['avg_eval_a_1_3'] = [];

        foreach ($resultQuery as $item) {
            $result['labels'][] = Yii::$app->formatter->asDate($item['date_reg']);
            $result['count_create'][] = $item['count_create'];
            $result['count_vote'][] = $item['count_vote'];
            $result['avg_eval_a_1_1'][] = $item['avg_eval_a_1_1'];
            $result['avg_eval_a_1_2'][] = $item['avg_eval_a_1_2'];
            $result['avg_eval_a_1_3'][] = $item['avg_eval_a_1_3'];
        }
        return $result;
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

    /**
     * @param $code
     * @return Organization
     * @throws NotFoundHttpException
     */
    protected function findModelOrganization($code)
    {
        if (($model = Organization::find()->where(['code'=>$code])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



}
