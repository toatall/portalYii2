<?php

namespace app\modules\executetasks\controllers;

use Yii;
use app\modules\executetasks\models\ExecuteTasks;
use app\modules\executetasks\models\ExecuteTasksDetail;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ManageController implements the CRUD actions for ExecuteTasks model.
 */
class ManageController extends Controller
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
                        'roles' => ['admin', ExecuteTasks::roleModerator()],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string $department
     * @param string $organization
     * @param int $period
     * @param int $periodYear
     * @return string
     */
    public function actionIndex($department=null, $organization=null, $period=null, $periodYear=null)
    {
        $modelTask = ExecuteTasks::findTaskByParmas($department, $organization, $period, $periodYear);        
        $dataProvider = new ArrayDataProvider([
            'allModels' => ($modelTask != null && $modelTask instanceof ExecuteTasks) ? $modelTask->executeTasksDetails : [],
            'pagination' => false,            
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'department' => $department,
            'organization' => $organization,
            'period' => $period,
            'periodYear' => $periodYear,
        ]);
    }


    /**
     * Детализация задач
     * @param string $department
     * @param string $organization
     * @param int $period
     * @param int $periodYear
     * @return string
     */
    public function actionDetailIndex($department, $organization, $period, $periodYear)
    {
        $modelTask = ExecuteTasks::findTaskByParmas($department, $organization, $period, $periodYear);        
        $dataProvider = new ArrayDataProvider([
            'allModels' => ($modelTask != null && $modelTask instanceof ExecuteTasks) ? $modelTask->executeTasksDetails : [],
            'pagination' => false,            
        ]);

        return $this->renderAjax('detail-index', [
            'dataProvider' => $dataProvider,
            'department' => $department,
            'organization' => $organization,
            'period' => $period,
            'periodYear' => $periodYear,
        ]);
    }

    /**
     * Добавление задачи
     * @param string $department
     * @param string $organization
     * @param int $period
     * @param int $periodYear
     * @return string
     */
    public function actionDetailCreate($department, $organization, $period, $periodYear)
    {
        $model = new ExecuteTasksDetail();
        $model->settings($department, $organization, $period, $periodYear);

        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return ['content' => 'ok'];
        }

        return [
            'title' => 'Добавление',
            'content' => $this->renderAjax('detail-form', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Изменение задачи
     * @param int $id
     * @return string
     */
    public function actionDetailUpdate($id)
    {
        $model = $this->findModel($id);

        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return ['content' => 'ok'];
        }

        return [
            'title' => 'Изменение',
            'content' => $this->renderAjax('detail-form', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Find record ExecuteTaskDetail by id
     * @param int $id
     * @return ExecuteTasksDetail
     */
    private function findModel($id)
    {
        if (($model = ExecuteTasksDetail::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}