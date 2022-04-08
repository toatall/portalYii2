<?php

namespace app\controllers;

use Yii;
use app\models\ExecuteTasks;
use app\models\Organization;
use app\modules\test\controllers\PublicController;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * ExecuteTasksController implements the CRUD actions for ExecuteTasks model.
 */
class ExecuteTasksController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'data-chart-radar'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['manage', 'manage-form', 'create', 'update', 'delete'],
                        'roles' => ['admin', ExecuteTasks::roleModerator()],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all ExecuteTasks models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ExecuteTasks::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExecuteTasks model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Управление данными по исполнению задач
     * @return mixed
     */
    public function actionManage()
    {
        return $this->render('manage');
    }

    public function actionManageForm($department, $period, $periodYear)
    {
        $models = ExecuteTasks::getModelsByPeriod($department, $period, $periodYear);
        $messageErrors = '';
        if (Model::loadMultiple($models, Yii::$app->request->post()) && Model::validateMultiple($models)) {
            foreach($models as $model) {
                if (!$model->save()) {
                    $messageErrors .= "Ошибка сохранения по организации {$model->org_code}<br />";
                }
            }
            if ($messageErrors != '') {
                Yii::$app->session->setFlash('danger', $messageErrors);
            }
            else {
                Yii::$app->session->setFlash('success', 'Данные сохранены!');
            }
        }

        return $this->renderAjax('ajaxManageForm', [
            'models' => $models,
        ]);
    }

    public function actionDataChartRadar($period, $periodYear)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $data = $this->getDataByPeriod($period, $periodYear);
        if ($data == null) {
            return null;
        }

        return [
            'general' => $this->getDataGeneral($data),
            'departments' => $this->getDataDepartments($data),
        ];
               
    }


    private function getDataGeneral($data)
    {
        // отбор отделов
        $departments = [];
        foreach ($data as $item) {
            if (!in_array($item['department_index'] . ' ' . $item['department_name'], $departments)) {
                $departments[] = $item['department_index'] . ' ' . $item['department_name'];
            }
        }
        ksort($departments);

        // данные, нужна сумма по отделу
        $dep = [];
        foreach($data as $i) {
            $val = 0;
            if ($i['count_tasks'] > 0) {
                $val = round($i['finish_tasks'] / $i['count_tasks'] * 100);
            }
            $dep[$i['department_index'] . ' ' . $i['department_name']][] = $val;
        }
        ksort($dep);

        $result = [
            'labels' => array_keys($dep),           
        ];

        $vals = [];
        foreach($dep as $d){
            $val = 0;
            if (count($i) > 0) {
                $val = round(array_sum($d) / count($d));
            }
            $vals[] = $val;
        }
        $result['datasets'][] = [
            'data' => $vals,
            'label' => 'Задачи',
            'borderColor' => 'rgb(' . $this->getRandomColor() . ')',
        ];
        return $result; 
    }

    private function getDataDepartments($data)
    {
        // // отбор отделов
        // $departments = [];
        // foreach ($data as $item) {
        //     if (!in_array($item['department_index'] . ' ' . $item['department_name'], $departments)) {
        //         $departments[] = $item['department_index'] . ' ' . $item['department_name'];
        //     }
        // }
        // ksort($departments);

        // // данные, нужна сумма по отделу
        // $dep = [];
        // foreach($data as $i) {
        //     $val = 0;
        //     if ($i['count_tasks'] > 0) {
        //         $val = round($i['finish_tasks'] / $i['count_tasks'] * 100);
        //     }
        //     $dep[$i['department_index'] . ' ' . $i['department_name']][] = $val;
        // }
        // ksort($dep);

        // $fullData = [];
        // foreach($data as $item) {
        //     $fullData[$item['org_code']] = $item;
        // }
        // ksort($fullData);


        $orgs = [];
        foreach ($data as $item) {
            if (!in_array($item['org_code'], $orgs)) {
                $orgs[] = $item['org_code'];
            }
        }        
        asort($orgs);

        $result = [
            'labels' => $orgs,
            'datasets' => [],
        ];
        foreach($data as $item) {
            //
            
        }

        return $result;


        return [
            'labels' => ['8600', '8601', '8602'],
            'datasets' => [
                [
                    'label' => '01 отдел',
                    'data' => [
                        rand(1, 100), rand(1, 100), rand(1, 100),
                    ],
                    'borderColor' => 'rgb(' . $this->getRandomColor() . ')',
                    //'backgroundColor' => 'rgb(' . $this->getRandomColor() . ')',
                ],
                [
                    'label' => '02 отдел',
                    'data' => [
                        rand(1, 100), rand(1, 100), rand(1, 100),
                    ],
                    'borderColor' => 'rgb(' . $this->getRandomColor() . ')',
                    //'backgroundColor' => 'rgb(' . $this->getRandomColor() . ')',
                ],
                [
                    'label' => '03 отдел',
                    'data' => [
                        rand(1, 100), rand(1, 100), rand(1, 100),
                    ],
                    'borderColor' => 'rgb(' . $this->getRandomColor() . ')',
                    //'backgroundColor' => 'rgb(' . $this->getRandomColor() . ')',
                ],
            ],
        ];



    }




    /**
     * @return string
     */
    private function getRandomColor()
    {
        return rand(0, 255) . ',' . rand(0, 255) . ',' . rand(0, 255);
    }

    private function getDataByPeriod($period, $periodYear)
    {
        return (new Query())
            ->select('t.*, org.name as org_name, dep.department_index, dep.department_name')
            ->from('{{%execute_tasks}} t')
            ->leftJoin('{{%organization}} org', 'org.code = t.org_code')
            ->leftJoin('{{%department}} dep', 'dep.id = t.id_department')
            ->where([
                't.period' => $period,
                't.period_year' => $periodYear,
            ])
            ->all();
    }

    /**
     * Creates a new ExecuteTasks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ExecuteTasks();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    

    /**
     * Updates an existing ExecuteTasks model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ExecuteTasks model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ExecuteTasks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ExecuteTasks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExecuteTasks::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
