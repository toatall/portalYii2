<?php

namespace app\modules\admin\controllers;

use app\models\Access;
use app\models\Tree;
use Yii;
use app\models\rating\RatingMain;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * RatingController implements the CRUD actions for RatingMain model.
 */
class RatingController extends Controller
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
     * Lists all RatingMain models.
     * @param $idTree
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionIndex($idTree)
    {
        // проверка прав и наличия действующего узла структуры
        $modelTree = $this->tree($idTree);

        $dataProvider = new ActiveDataProvider([
            'query' => RatingMain::find()->where(['id_tree' => $idTree]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'modelTree' => $modelTree,
        ]);
    }

    /**
     * Displays a single RatingMain model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'modelTree' => $model->tree,
        ]);
    }

    /**
     * Creates a new RatingMain model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $idTree
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionCreate($idTree)
    {
        $modelTree = $this->tree($idTree);

        $model = new RatingMain();

        if ($model->load(Yii::$app->request->post())) {
            $model->id_tree = $idTree;
            $model->uploadFiles = UploadedFile::getInstances($model, 'uploadFiles');
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelTree' => $modelTree,
        ]);
    }

    /**
     * Updates an existing RatingMain model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
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
            'modelTree' => $model->tree,
        ]);
    }

    /**
     * Deletes an existing RatingMain model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['index', 'idTree' => $model->id_tree]);
    }

    /**
     * Finds the RatingMain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RatingMain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
     */
    protected function findModel($id)
    {
        if (($model = RatingMain::findOne($id)) !== null) {
            if (!Access::checkAccessUserForTree($model->id_tree)) {
                throw new ForbiddenHttpException('Доступ запрещен');
            }
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $id
     * @return Tree
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    protected function tree($id)
    {
        if (($model = Tree::findOne(['id' => $id, 'module' => RatingMain::getModule()])) == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (!Access::checkAccessUserForTree($id)) {
            throw new ForbiddenHttpException('Доступ запрещен');
        }

        return $model;
    }
}
