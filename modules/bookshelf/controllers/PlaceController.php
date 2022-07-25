<?php

namespace app\modules\bookshelf\controllers;

use app\modules\bookshelf\models\BookShelf;
use Yii;
use app\modules\bookshelf\models\BookShelfPlace;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * PlaceController implements the CRUD actions for BookShelfPlace model.
 */
class PlaceController extends Controller
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
     * Lists all BookShelfPlace models.
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $dataProvider = new ActiveDataProvider([
            'query' => BookShelfPlace::find(),
        ]);

        return [
            'title' => 'Управление расположением книг',
            'content' => $this->renderAjax('index', [
                'dataProvider' => $dataProvider,
            ]),
        ];
    }    

    /**
     * Creates a new BookShelfPlace model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BookShelfPlace();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return '<ok/>';
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BookShelfPlace model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {            
            return '<ok/>';
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BookShelfPlace model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return '<ok/>';
    }

    /**
     * Finds the BookShelfPlace model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BookShelfPlace the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BookShelfPlace::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
