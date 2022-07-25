<?php

namespace app\controllers;

use app\models\Organization;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use app\components\Controller;

/**
 * OrganizationController implements the CRUD actions for Organization model.
 */
class OrganizationController extends Controller
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
                        'actions' => ['index', 'view', 'about', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['create', 'delete'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Organization models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Organization::find()
                ->where([
                    'date_end' => null,
                ])
                ->andWhere(['not', ['code' => '8600']])
                ->orderBy(['name' => SORT_ASC]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Organization model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Информация об организации
     * @param string $code
     * @return string
     */
    public function actionAbout($code)
    {
        $model = $this->findModel($code);
        return $this->renderAjax('about', [
            'model' => $model,
        ]);
    }

    /**
     * @param string $code
     * @return string
     */
    public function actionUpdate($code)
    {
        if (!Organization::isRoleModerator($code)) {
            throw new ForbiddenHttpException();
        }
        $model = $this->findModel($code);
        $model->scenario = 'update-history-reference';

        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->uploadImages = UploadedFile::getInstances($model, 'uploadImages'); 
            $model->upload();    
            return ['content' => 'OK', 'updateId' => '#org_container_1'];
        }
                
        return [
            'title' => 'Обновление исторической справки ('. $model->name .')',
            'content' => $this->renderAjax('form', [
                'model' => $model,
            ]),
        ];
    }


    /**
     * Finds the Organization model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Organization the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Organization::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
