<?php

namespace app\modules\bookshelf\controllers;

use Yii;
use app\modules\bookshelf\models\BookShelfCalendar;
use app\modules\bookshelf\models\BookCalendarSearch;
use app\modules\bookshelf\models\BookShelf;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * CalendarController implements the CRUD actions for BookShelfCalendar model.
 */
class CalendarController extends Controller
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
                        'actions' => ['index', 'view'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete'],
                        'roles' => ['admin', BookShelf::roleAdmin()],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all BookShelfCalendar models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookCalendarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BookShelfCalendar model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        
        $d1 = Yii::$app->formatter->asDate($model->date_birthday);
        $d2 = Yii::$app->formatter->asDate($model->date_die);

        return [
            'title' => $model->writer . "<br /><span class=\"lead\">($d1 - $d2)</span>",
            'content' => $this->renderAjax('view', [
                'model' => $model,                
            ]),
        ];
    }

    /**
     * Creates a new BookShelfCalendar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new BookShelfCalendar();

        if ($model->load(Yii::$app->request->post())) {
            $model->uploadPhoto = UploadedFile::getInstance($model, 'uploadPhoto');
            if ($model->save()) {
                return 'OK';
            }
        }

        return [
            'title' => 'Добавление писателя',
            'content' => $this->renderAjax('_form', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Updates an existing BookShelfCalendar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->uploadPhoto = UploadedFile::getInstance($model, 'uploadPhoto');
            if ($model->save()) {
                return 'OK';
            }
        }

        return [
            'title' => 'Добавление писателя',
            'content' => $this->renderAjax('_form', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Deletes an existing BookShelfCalendar model.
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
     * Finds the BookShelfCalendar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BookShelfCalendar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BookShelfCalendar::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
