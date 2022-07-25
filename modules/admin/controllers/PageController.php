<?php

namespace app\modules\admin\controllers;

use app\helpers\DateHelper;
use app\models\Access;
use app\models\page\PageSearch;
use app\models\Tree;
use Yii;
use app\models\page\Page;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
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
     * Lists all Page models.
     * @param $idTree
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionIndex($idTree)
    {
        // проверка прав и наличия действующего узла структуры
        $modelTree = $this->tree($idTree);

        $searchModel = new PageSearch();
        $dataProvider = $searchModel->searchBackend(Yii::$app->request->queryParams, $idTree);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelTree' => $modelTree,
        ]);
    }

    /**
     * Displays a single Page model.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $idTree
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionCreate($idTree)
    {
        $modelTree = $this->tree($idTree);
        $model = new Page();
        $model->date_start_pub = DateHelper::today();
        $model->date_end_pub = DateHelper::maxDate();
        $model->flag_enable = true;

        if ($model->load(Yii::$app->request->post())) {
            $model->id_tree = $idTree;
            $model->id_organization = Yii::$app->userInfo->current_organization;

            $model->uploadThumbnailImage = UploadedFile::getInstance($model, 'uploadThumbnailImage');
            $model->uploadFiles = UploadedFile::getInstances($model, 'uploadFiles');
            $model->uploadImages = UploadedFile::getInstances($model, 'uploadImages');

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
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->uploadThumbnailImage = UploadedFile::getInstance($model, 'uploadThumbnailImage');
            $model->uploadFiles = UploadedFile::getInstances($model, 'uploadFiles');
            $model->uploadImages = UploadedFile::getInstances($model, 'uploadImages');
            
            if ($model->save()) {        
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->user->can('admin')) {
            $model->delete();
        }
        else {
            $model->date_delete = new Expression('getdate()');
            $model->save();
        }

        return $this->redirect(['index', 'idTree' => $model->id_tree]);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
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
        if (($model = Tree::findOne(['id' => $id, 'module' => Page::getModule()])) == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (!Access::checkAccessUserForTree($id)) {
            throw new ForbiddenHttpException('Доступ запрещен');
        }

        return $model;
    }
}
