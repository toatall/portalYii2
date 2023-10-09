<?php

namespace app\modules\kadry\modules\beginner\controllers;

use app\modules\kadry\modules\beginner\models\Beginner;
use app\modules\kadry\modules\beginner\models\BeginnerSearch;
use Yii;
use app\components\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * DefaultController implements the CRUD actions for Beginner model.
 */
class DefaultController extends Controller
{
    
    /**
     * {@inheritdoc}
     * @return array
     */
    public function actions(): array 
    {
        return array_merge(parent::actions(), [
            'delete-files' => \app\widgets\FilesGallery\DeleteFileAction::class,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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
                            'roles' => ['admin', Beginner::getRoleModerator()],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['delete-files'],
                            'roles' => ['admin', Beginner::getRoleModerator()],
                            'matchCallback' => function() {
                                $id = Yii::$app->request->get('id');
                                return $this->checkModelAccess($id);
                            },
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Beginner models.
     *
     * @return string
     */
    public function actionIndex()
    {       
        $searchModel = new BeginnerSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Beginner model.
     * @param int $id ИД
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = $this->findModel($id);

        return [
            'title' => $model->fio,
            'content' => $this->renderAjax('view', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Creates a new Beginner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {        
        $model = new Beginner([
            'org_code' => Yii::$app->user->identity->default_organization,
        ]);

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $model->uploadThumn();
                $model->uploadFilesGallery();
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
     * Updates an existing Beginner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ИД
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            $model->uploadThumn();
            $model->uploadFilesGallery();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Beginner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ИД
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Beginner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ИД
     * @return Beginner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Beginner::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * Ограничение на удаление файлов
     * @param int $id 
     * @return bool
     */
    protected function checkModelAccess($id) 
    {
        if ($id === null) {
            return false;
        }
        return true;
    }
}
