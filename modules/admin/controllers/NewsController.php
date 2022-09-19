<?php

namespace app\modules\admin\controllers;

use app\helpers\DateHelper;
use app\models\Access;
use Yii;
use app\models\news\News;
use app\models\news\NewsSearch;
use yii\base\InvalidConfigException;
use yii\db\Expression;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Tree;
use kartik\grid\EditableColumnAction;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
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
     * {@inheritdoc}
     * @return array
     */
    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'editnews' => [
                'class' => EditableColumnAction::class,
                'modelClass' => News::class,
                'outputValue' => function($model, $attribute, $key, $index) {
                    if ($attribute == 'flag_enable') {
                        return Yii::$app->formatter->asBoolean($model->flag_enable);
                    }
                },
                'checkAccess' => function($action, $model) {
                    /** @var News $model */
                    if (isset($model->id_tree)) {
                        $this->tree($model->id_tree);
                    }
                    else {
                        throw new ForbiddenHttpException('Доступ запрещен');
                    }                    
                },
            ],
        ]);
    }

    /**
     * Lists all News models.
     * @param $idTree
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionIndex($idTree)
    {
        // проверка прав и наличия действующего узла структуры
        $modelTree = $this->tree($idTree);

        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->searchBackend(Yii::$app->request->queryParams, $idTree);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelTree' => $modelTree,
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $modelTree = $model->tree;
        return $this->render('view', [
            'model' => $model,
            'modelTree' => $modelTree,
        ]);
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $idTree int
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionCreate($idTree)
    {
        $modelTree = $this->tree($idTree);
        $model = new News();
        if (Yii::$app->userInfo->current_organization == '8600') {
            $model->scenario = News::SCENARIO_DEPARTMENT_REQUIRED;
        }
        $model->date_start_pub = DateHelper::today();
        $model->date_end_pub = DateHelper::maxDate();
        $model->flag_enable = true;
        $model->on_general_page = (Yii::$app->userInfo->current_organization == '8600');
        $model->message2 = '<p style="font-size:20px;"></p>';

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
     * Updates an existing News model.
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

            $model->uploadThumbnailImage = UploadedFile::getInstance($model, 'uploadThumbnailImage');
            $model->uploadFiles = UploadedFile::getInstances($model, 'uploadFiles');
            $model->uploadImages = UploadedFile::getInstances($model, 'uploadImages');

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
     * Deletes an existing News model.
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
     * Ajax-запрос списка отделов
     * @param string|null $q
     * @return mixed
     * @uses \app\modules\admin\views\news\_form
     */
    public function actionAjaxDepartmentsAuthors($q=null)
    {
        $records = (new Query())
            ->from('{{%news}}')
            ->where([
                'id_organization' => Yii::$app->userInfo->current_organization,             
            ])
            ->andFilterWhere(['like', 'from_department', $q])
            ->andWhere(['not', ['from_department' => null]])
            ->andWhere(['not', ['from_department' => '']])
            ->select('from_department')
            ->orderBy('from_department')
            ->groupBy('from_department')
            ->all();
        
        $out = [];
        foreach ($records as $item) {          
            $out[] = $item['from_department'];
        }
        return Json::encode($out);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionHistory($id)
    {
        $model = $this->findModel($id);
        $this->tree($model->id_tree);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $model->getHistory(),
        ]);
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => 'Статистика просмотров страницы "' . $model->title . '"',
            'content' => $this->renderAjax('/news/table_history', [
                'dataProvider' => $dataProvider,
            ]),
        ];        
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionLikes($id)
    {
        $model = $this->findModel($id);
        $this->tree($model->id_tree);

        $dataProvider = new ActiveDataProvider([
            'query' => $model->getLikes(),
        ]);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => 'Статистика лайков страницы "' . $model->title . '"',
            'content' => $this->renderAjax('/news/table_likes', [
                'dataProvider' => $dataProvider,
            ]),
        ];
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
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
        if (($model = Tree::findOne(['id' => $id, 'module' => News::getModule()])) == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (!Access::checkAccessUserForTree($id)) {
            throw new ForbiddenHttpException('Доступ запрещен');
        }

        return $model;
    }
}
