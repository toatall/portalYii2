<?php

namespace app\modules\contest\modules\pets\controllers;


use app\components\Controller;
use app\modules\contest\modules\pets\models\Pets;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{

    public $layout = 'main';
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }
    
    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $models = Pets::find()->all();
        return $this->render('index', [
            'models' => $models,
        ]);
    }

    
    /**
     * Displays a single Pets model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $this->titleAjaxResponse = $model->pet_name;
        return $this->render('view', [
            'model' => $model,
        ]);
    }


    /**
     * Creates a new Pets model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Pets();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $model->uploadFiles = UploadedFile::getInstances($model, 'uploadFiles');
                $model->upload();
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
     * Updates an existing Pets model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            $model->uploadFiles = UploadedFile::getInstances($model, 'uploadFiles');
            $model->upload();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Pets model.
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
     * Finds the Pets model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Pets|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pets::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
}
