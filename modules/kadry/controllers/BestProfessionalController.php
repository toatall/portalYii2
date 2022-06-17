<?php

namespace app\modules\kadry\controllers;

use Yii;
use app\modules\kadry\models\BestProfessional;
use app\modules\kadry\models\BestProfessionalSearch;
use yii\debug\models\timeline\DataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * BestProfessionalController implements the CRUD actions for BestProfessional model.
 */
class BestProfessionalController extends Controller
{

    public $layout = 'bestProfessional';

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
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['create', 'update',  'delete'],
                        'allow' => true,
                        'roles' => ['admin', BestProfessional::roleModerator()],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all BestProfessional models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BestProfessionalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }    

    /**
     * Просмотрs
     * @param int $id
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new BestProfessional model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BestProfessional();
        $model->period_year = date('Y');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->uploadImage = UploadedFile::getInstances($model, 'uploadImage'); 
            $model->upload();      
            return $this->returnJsOK();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        
        return [
            'title' => 'Добавить',
            'content' => $this->renderAjax('_form', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Updates an existing BestProfessional model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->uploadImage = UploadedFile::getInstances($model, 'uploadImage'); 
            $model->upload();      
            return $this->returnJsOK();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'title' => 'Изменение записи ' . $model->fio,
            'content' => $this->renderAjax('_form', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Deletes an existing BestProfessional model.
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
     * Finds the BestProfessional model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BestProfessional the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BestProfessional::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @return string
     */
    private function returnJsOK()
    {               
        return <<<HTML
            <script type="text/javascript">
                modalViewer.closeModal();
                $.pjax.reload({ container: '#pjax-best-professional-index', async: false });
            </script>
        HTML;
    }

}
