<?php

namespace app\modules\executetasks\controllers;

use Yii;
use app\modules\executetasks\models\ExecuteTasks;
use app\modules\executetasks\models\ExecuteTasksChart;
use app\modules\executetasks\models\ExecuteTasksDescriptionDepartment;
use app\modules\executetasks\models\ExecuteTasksDescriptionOrganization;
use yii\base\DynamicModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;

/**
 * DefaultController implements the CRUD actions for ExecuteTasks model.
 */
class DefaultController extends Controller
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
                        'roles' => ['@'],
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
            'periods' => $this->getUsePeriods(),
        ]);
    }


    /**
     * Вернуть используемые периоды 
     * @return array
     */
    protected function getUsePeriods()
    {
        $query = (new Query())
            ->select('period, period_year')
            ->from('{{%execute_tasks}}')
            ->groupBy('period, period_year')
            ->orderBy([
                'period_year' => SORT_DESC,
                'period' => SORT_DESC,
            ])
            ->limit(8)
            ->all();
        
        $result = [];
        foreach($query as $item) {
            $url = Url::to(['/executetasks/default/data', 'period'=>$item['period'], 'periodYear'=>$item['period_year']]);
            $result[$url] = $item['period'] . ' квартал ' . $item['period_year'];
        }
        return $result;
    }


    /**
     * Информация для основных графиков на главной странице
     * @param string $period
     * @param string $periodYear
     */
    public function actionData($period, $periodYear)
    {
        $baseData = ExecuteTasksChart::getDataByPeriod($period, $periodYear);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'total' => [ExecuteTasksChart::getTotal($baseData)],
            'totalWithIndex' => ExecuteTasksChart::getTotalWithIndex($baseData),
            'departments' => ExecuteTasksChart::getDepartments($baseData, $period, $periodYear),
            'organizations' => ExecuteTasksChart::getOrganizations($baseData, $period, $periodYear),            
        ];
    }

    /**
     * Информация для графика по отделу в разрезе организаций
     * @param int $idDepartment
     * @param int $idOrganization
     * @param string $period
     * @param string $periodYear
     * @return array json
     */
    public function actionDataOrganization($idDepartment, $idOrganization, $period, $periodYear)
    {
        $query = (new Query())
            ->select('t.*')
            ->from('{{%execute_tasks_detail}} t')
            ->leftJoin('{{%execute_tasks}} parent', 't.id_task = parent.id')
            ->where([
                'parent.org_code' => $idOrganization,
                'parent.id_department' => $idDepartment,
                'parent.period' => $period,
                'parent.period_year' => $periodYear,
            ])
            ->orderBy(['t.name' => SORT_ASC])
            ->all();
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
        ]);
        $modelDepartmentEmployee = ExecuteTasksDescriptionDepartment::findOne(['id_department' => $idDepartment]);
        $departmentName = (isset($modelDepartmentEmployee->department)) ? $modelDepartmentEmployee->department->getConcatened() : null;

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'table' => $this->renderAjax('detail_table', [
                'dataProvider' => $dataProvider,
            ]),
            'employee' => $this->renderAjax('detail_photo', [
                'model' => $modelDepartmentEmployee,
            ]),
            'deaprtmentName' => $departmentName,
        ];        
    }    

    /**
     * Информация для графика по организации в разрезе отделов
     * @param int $idOrganization
     * @param string $period
     * @param string $periodYear
     * @return array json
     */
    public function actionDataDepartment($idOrganization, $period, $periodYear)
    {
        $query = (new Query())
            ->select('t.*, dep.department_index, dep.department_name, dep.short_name')
            ->from('{{%execute_tasks}} t')
            ->leftJoin('{{%department}} dep', 'dep.id = t.id_department')
            ->where([
                't.org_code' => $idOrganization,
                't.period' => $period,
                't.period_year' => $periodYear,
            ])
            ->orderBy(['dep.department_index' => SORT_ASC])
            ->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => false,
        ]);
        $modelOrganizationEmployee = ExecuteTasksDescriptionOrganization::findOne(['code_org' => $idOrganization]);
        $organizationName = (isset($modelOrganizationEmployee->codeOrg)) ? $modelOrganizationEmployee->codeOrg->fullName : $idOrganization;
       
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'table' => $this->renderAjax('task_table', [
                'dataProvider' => $dataProvider,
                'idOrganization' => $idOrganization, 
                'period' => $period, 
                'periodYear' => $periodYear,               
            ]),
            'employee' => $this->renderAjax('detail_photo', [
                'model' => $modelOrganizationEmployee,
            ]),
            'organizationName' => $organizationName,
        ];    
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
    

    

   


    // public function actionManageForm($department, $organization, $period, $periodYear)
    // {
    //     $models = ExecuteTasks::getTasks($department, $organization, $period, $periodYear);

    //     // $models = ExecuteTasks::getModelsByPeriod($department, $period, $periodYear);
    //     // $messageErrors = '';
    //     // if (Model::loadMultiple($models, Yii::$app->request->post()) && Model::validateMultiple($models)) {
    //     //     foreach($models as $model) {
    //     //         if (!$model->save()) {
    //     //             $messageErrors .= "Ошибка сохранения по организации {$model->org_code}<br />";
    //     //         }
    //     //     }
    //     //     if ($messageErrors != '') {
    //     //         Yii::$app->session->setFlash('danger', $messageErrors);
    //     //     }
    //     //     else {
    //     //         Yii::$app->session->setFlash('success', 'Данные сохранены!');
    //     //     }
    //     // }

    //     // return $this->renderAjax('ajaxManageForm', [
    //     //     'models' => $models,
    //     // ]);
    //     $dataProvider = new ArrayDataProvider([
    //         'allModels' => $models,
    //         'pagination' => false,            
    //     ]);

    //     return $this->renderAjax('ajaxManage', [
    //         'dataProvider' => $dataProvider,
    //         'department' => $department,
    //         'organization' => $organization,
    //         'period' => $period,
    //         'periodYear' => $periodYear,
    //     ]);

    // }

    /**
     * @param string $department
     * @param string $organization
     * @param int $period
     * @param int $periodYear
     */
    public function actionTaskDetailCreate($department, $organization, $period, $periodYear)
    {        
        $model = $this->getModelForm();

        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            return ['content' => 'ok'];
        }
        
        return [
            'title' => 'Добавление задачи',
            'content' => $this->renderAjax('ajaxManageDetailForm', [
                'model' => $model,
            ]),
        ];
    }

    private function getModelForm()
    {
        $model = new DynamicModel(['name', 'count_tasks', 'finish_tasks']);  
        $model->addRule(['name', 'count_tasks', 'finish_tasks'], 'required');
        $model->addRule(['count_tasks', 'finish_tasks'], 'number');
        $model->setAttributeLabels([
            'name' => 'Наименование',
            'count_tasks' => 'Количество задач',
            'finish_tasks' => 'Количество завершенных задач',
        ]);
        return $model;
    }




    public function actionDataChartRadar($period, $periodYear)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $data = $this->getDataByPeriod($period, $periodYear);
        if ($data == null) {
            return null;
        }
        
        return [
            'general' => $this->getDataGeneral($data, $period, $periodYear),
            'departments' => $this->getDataDepartments($data),
        ];
               
    }


    private function getDataGeneral($data, $preiod, $periodYear)
    {
        // отбор отделов
        $departments = [];
        foreach ($data as $item) {
            if (!in_array($item['department_index'] . ' ' . $item['department_name'], $departments)) {
                $departments[$item['department_id']] = $item['department_index'] . ' ' . $item['department_name'];
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
            'backgroundColor' => 'rgb(' . $this->getRandomColor() . ')',        
        ];        

        // links
        foreach($departments as $id=>$val) {
            $result['links'][] = Url::to(['/execute-tasks/view-department', 'id'=>$id, 'period'=>$preiod, 'periodYear'=>$periodYear]);
        }

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
            ->select('t.*, org.name as org_name, dep.id department_id, dep.department_index, dep.department_name')
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
