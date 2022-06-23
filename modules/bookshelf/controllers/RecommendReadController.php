<?php

namespace app\modules\bookshelf\controllers;

use app\modules\bookshelf\models\BookShelf;
use app\modules\bookshelf\models\RecommendRead;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * RecommendReadController implements the CRUD actions for RecommendRead model.
 */
class RecommendReadController extends Controller
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
                        'roles' => ['admin', BookShelf::roleAdmin()],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all RecommendRead models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => RecommendRead::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }    

    /**
     * Creates a new RecommendRead model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new RecommendRead();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {          
            return 'OK';
        }

        return [
            'title' => 'Добавление',
            'content' => $this->renderAjax('_form', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Updates an existing RecommendRead model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {                 
            return 'OK';            
        }

        return [            
            'title' => "Редактирование {$model->fio}",
            'content' => $this->renderAjax('_form', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Deletes an existing RecommendRead model.
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
     * Finds the RecommendRead model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RecommendRead the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RecommendRead::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
