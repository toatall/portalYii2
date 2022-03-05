<?php

namespace app\modules\bookshelf\controllers;

use Yii;
use app\modules\bookshelf\models\BookShelf;
use app\modules\bookshelf\models\BookShelfRating;
use app\modules\bookshelf\models\BookShelfSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * BookController implements the CRUD actions for BookShelf model.
 */
class BookController extends Controller
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
                    'save-rating' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'save-rating'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all BookShelf models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookShelfSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BookShelf model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {        
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        
        $modelRating = $this->findModelSHelfRating($id);
        if ($modelRating->load(Yii::$app->request->post()) && $modelRating->save()) {}        
        return [
            'title' => $model->title . " ({$model->writer})",
            'content' => $this->renderAjax('view', [
                'model' => $model,
                'modelRating' => $modelRating,
            ]),
        ];
    }

    /**
     * Creates a new BookShelf model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new BookShelf();

        if ($model->load(Yii::$app->request->post())) {
            $model->uploadPhoto = UploadedFile::getInstance($model, 'uploadPhoto');
            if ($model->save()) {
                return 'OK';
            }
        }

        return [
            'title' => 'Добавление книги',
            'content' => $this->renderAjax('_form', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Updates an existing BookShelf model.
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
            'title' => "Изменение книги {$model->title}",
            'content' => $this->renderAjax('_form', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Deletes an existing BookShelf model.
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
     * Finds the BookShelf model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BookShelf the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BookShelf::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Поиск модели голосования
     * Если нет такой, то создание новой
     * @return BookShelfRating
     */
    protected function findModelSHelfRating($idShelf)
    {
        $model = BookShelfRating::find()->where([
            'id_book_shelf' => $idShelf,
            'username' => Yii::$app->user->identity->username,
        ])->one();
        if ($model === null) {
            $model = new BookShelfRating([
                'id_book_shelf' => $idShelf,
            ]);
        }
        return $model;
    }
}
