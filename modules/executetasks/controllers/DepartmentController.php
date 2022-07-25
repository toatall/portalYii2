<?php

namespace app\modules\executetasks\controllers;

use app\modules\executetasks\models\ExecuteTasks;
use Yii;
use app\modules\executetasks\models\ExecuteTasksDescriptionDepartment;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DepartmentController implements the CRUD actions for ExecuteTasksDepartment model.
 */
class DepartmentController extends Controller
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
     * Lists all ExecuteTasksDepartment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ExecuteTasksDescriptionDepartment::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }   

    /**
     * Creates a new ExecuteTasksDepartment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ExecuteTasksDescriptionDepartment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->uploadImage = UploadedFile::getInstances($model, 'uploadImage'); 
            $model->upload();    
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ExecuteTasksDepartment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->uploadImage = UploadedFile::getInstances($model, 'uploadImage'); 
            $model->upload();    
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ExecuteTasksDepartment model.
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
     * Finds the ExecuteTasksDepartment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ExecuteTasksDepartment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExecuteTasksDescriptionDepartment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
