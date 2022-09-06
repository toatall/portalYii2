<?php

namespace app\modules\paytaxes\controllers;

use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Response;
use app\components\Controller;
use app\modules\paytaxes\models\PayTaxesChartDay;
use app\modules\paytaxes\models\PayTaxesChartMonth;
use app\modules\paytaxes\models\PayTaxesGeneral;
use app\modules\paytaxes\Module;

class ManageController extends Controller
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
                        'roles' => ['admin', Module::roleEditor()],
                    ],
                ],
            ],
        ];
    }

    
    /**
     * Главная страница ввода данных
     * @return string
     */
    public function actionIndex()
    {       
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => 'Ввод данных',
            'content' => $this->renderAjax('index'),
        ];                    
    }

    /**
     * @return array
     */
    private function getOrganizations()
    {
        return (new Query())
            ->from('{{%organization}}')
            ->where(['in', 'code', ['8600','8601','8602','8603','8606','8617','8619']])
            ->orderBy('code')
            ->indexBy('code')
            ->all();
    }

    /**
     * Просмотр/сохранение данных
     * @return string
     */
    public function actionAdminData($type, $date=null, $month=null)
    {
        if ($date == null) {
            $date = Yii::$app->formatter->asDate('today');
        }

        $orgs = $this->getOrganizations();


        // >> главная таблица

        if ($type == 1) {
            
            $models = [];
            foreach($orgs as $org) {
                $model = PayTaxesGeneral::find()->where([
                    'code_org' => $org['code'],
                    'date' => $date,
                ])->one();
                if ($model === null) {
                    $model = new PayTaxesGeneral();
                    $model->code_org = $org['code'];
                    $model->date = $date;
                }
                $models[$org['code']] = $model;
            }

            // validate and save
            if (PayTaxesGeneral::loadMultiple($models, Yii::$app->request->post())) {
                if (PayTaxesGeneral::validateMultiple($models)) {
                    foreach ($models as $model) {
                        $model->save();                        
                    }      
                }                                
            }
            
            return $this->renderAjax('edit-general', [               
                'models' => $models,
            ]);
        }

        // << главная страница


        // >> для графика по дням
        
        if ($type == 2) {

            $models = [];
            foreach($orgs as $org) {
                $model = PayTaxesChartDay::find()->where([
                    'code_org' => $org['code'],
                    'date' => $date,
                ])
                ->one();
                if ($model === null) {
                    $model = new PayTaxesChartDay();
                    $model->code_org = $org['code'];
                    $model->date = $date;
                }
                $models[$org['code']] = $model;
            }

            // validate and save
            if (PayTaxesChartDay::loadMultiple($models, Yii::$app->request->post()) && 
                PayTaxesChartDay::validateMultiple($models)) {
                foreach ($models as $model) {
                    $model->save();
                }
            }

            return $this->renderAjax('edit-chart-day', [
                'models' => $models,                
            ]);
        }

        // << для графика по дням


        // >> для графика по месяцам

        if ($type == 3) {

            $y = date('Y');

            $models = [];
            foreach ($orgs as $org) {
                $model = PayTaxesChartMonth::find()->where([
                    'month' => $month,
                    'year' => $y,
                    'code_org' => $org['code'],
                ])
                ->one();
                if ($model === null) {
                    $model = new PayTaxesChartMonth();
                    $model->code_org = $org['code'];
                    $model->month = $month;
                    $model->year = $y;
                }
                $models[$org['code']] = $model;
            }

            // validate and save
            if (PayTaxesChartMonth::loadMultiple($models, Yii::$app->request->post()) && 
                PayTaxesChartMonth::validateMultiple($models)) {
                foreach ($models as $model) {
                    $model->save();
                }
            }

            return $this->renderAjax('edit-chart-month', [
                'models' => $models,
            ]);
        }

        // << для графика по месяцам
    }      


}