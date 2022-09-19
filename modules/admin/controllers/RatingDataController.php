<?php

namespace app\modules\admin\controllers;

use app\models\Access;
use app\models\rating\RatingMain;
use Yii;
use app\models\rating\RatingData;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * RatingDataController implements the CRUD actions for RatingData model.
 */
class RatingDataController extends Controller
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
     * Lists all RatingData models.
     * @param $idMain
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionIndex($idMain)
    {
        // проверка прав и наличия действующего узла структуры
        $modelRatingMain = $this->findModelRatingMain($idMain);

        $dataProvider = new ActiveDataProvider([
            'query' => RatingData::find()
                ->orderBy('rating_year desc, rating_period desc')
                ->where(['id_rating_main' => $idMain]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'modelRatingMain' => $modelRatingMain,
            'modelTree' => $modelRatingMain->tree,
        ]);
    }

    /**
     * Displays a single RatingData model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $modelRatingMain = $model->ratingMain;
        $modelTree = $modelRatingMain->tree;
        return $this->render('view', [
            'model' => $model,
            'modelRatingMain' => $modelRatingMain,
            'modelTree' => $modelTree,
        ]);
    }

    /**
     * Creates a new RatingData model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $idMain
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionCreate($idMain)
    {
        $modelRatingMain = $this->findModelRatingMain($idMain);
        $model = new RatingData();
        $model->id_rating_main = $idMain;
        $model->rating_year = date('Y');

        if ($model->load(Yii::$app->request->post())) {
            $model->uploadFiles = UploadedFile::getInstances($model, 'uploadFiles');
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelRatingMain' => $modelRatingMain,
            'modelTree' => $modelRatingMain->tree,
        ]);
    }

    /**
     * Updates an existing RatingData model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->uploadFiles = UploadedFile::getInstances($model, 'uploadFiles');
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelTree' => $model->ratingMain->tree,
        ]);
    }

    /**
     * Deletes an existing RatingData model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['index', 'idMain' => $model->id_rating_main]);
    }

    /**
     * Finds the RatingData model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RatingData the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RatingData::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Поиск вида рейтирга и проаверка наличия прав
     * @param $id
     * @return RatingMain|null
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    protected function findModelRatingMain($id)
    {
        if (($model = RatingMain::findOne($id)) == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if (!Access::checkAccessUserForTree($model->id_tree)) {
            throw new ForbiddenHttpException('Доступ запрещен');
        }
        return $model;
    }

}
