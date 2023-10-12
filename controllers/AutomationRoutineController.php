<?php

namespace app\controllers;

use app\models\AutomationRoutine;
use app\models\AutomationRoutineSearch;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\NotFoundHttpException;

/**
 * AutomationRoutineController implements the CRUD actions for AutomationRoutine model.
 */
class AutomationRoutineController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'download'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // [
                    //     'allow' => true,
                    //     'roles' => ['admin'],
                    // ],
                ],
            ],
        ];
    }

    /**
     * Lists all AutomationRoutine models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AutomationRoutineSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AutomationRoutine model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if (isset($_POST['rate'])) {
            $rate = $_POST['rate'];
            $model->updateRate($rate);
        }
        
        $this->titleAjaxResponse = $model->title;
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Загрузка файла
     * @return mixed
     */
    public function actionDownload($id, $f)
    {
        $model = $this->findModel($id);
        $model->saveDownload($f);
        return $this->redirect($f);
    }

    /**
     * Creates a new AutomationRoutine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new AutomationRoutine();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {                
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AutomationRoutine model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {                       
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AutomationRoutine model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AutomationRoutine model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return AutomationRoutine|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AutomationRoutine::findOne(['id' => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
