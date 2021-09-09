<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\models\Organization;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                        'actions' => ['change-organization', 'list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    /**
     * Изменение кода организации у текущего пользователя и переадресация `назад`
     * @param type $code
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionChangeOrganization($code)
    {        
        $model = $this->findModel(\Yii::$app->user->id);        
        $organizations = ArrayHelper::map($model->organizations, 'code', 'code');
        if (!isset($organizations[$code])) {
            if (!$this->isOrganization($code)) {
                throw new ServerErrorHttpException("Организация с кодом $code не найдена");
            }
            else {
                throw new ServerErrorHttpException("Отсутвует доступ к организации с кодом $code");
            }
        }        
        $model->changeOrganization($code);
        \Yii::$app->userInfo->clearSession(); // для того, чтобы в сессии тоже изменился код организации   
        if (Yii::$app->request->referrer) {
            return $this->redirect(Yii::$app->request->referrer);            
        }
        else {
            return $this->redirect(['/admin/default/index']);
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    protected function isOrganization($code)
    {
        return Organization::find()->where(['code'=>$code])->exists();
    }

    /**
     * Вывод списка пользователей
     * @param array|null $users
     * @param array|null $idGroup
     * @return array|string
     * @uses \app\modules\admin\controllers\TreeController::actionCreate()
     * @uses \app\modules\admin\controllers\TreeController::actionUpdate()
     */
    public function actionList($users=null, $idGroup=null)
    {
        $searchModel = new User();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $users, $idGroup);
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => 'Добавление пользователя',
                'content' => $this->renderAjax('list', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ]),
            ];
        }
        else {
            return $this->renderAjax('list', [
                'dataProvider' => $dataProvider,
            ]);
        }
    }
    
}
