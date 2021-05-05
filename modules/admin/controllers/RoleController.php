<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\Role;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * RoleController implements the CRUD actions for Role model.
 */
class RoleController extends Controller
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
                    'delete-sub-user' => ['POST'],
                    'delete-sub-role' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Role models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Role::find(),
            'pagination' => [
                'pageSize' => $this->getPaginzationSize(),
            ],
        ]);
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Role model.
     * @param string $id
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
     * Creates a new Role model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \Exception
     */
    public function actionCreate()
    {
        $model = new Role();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->name]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Role model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->name]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Role model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
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
     * Изменение состава группы
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAdmin($id)
    {
        $model = $this->findModel($id);

        return $this->render('admin', [
            'model' => $model,
        ]);
    }


    // Sub users

    /**
     * Добавление пользователю роли
     * @param $id иднтификатор роли
     * @param null $userId идентификатор пользователя
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\ExitException
     */
    public function actionAddSubUser($id, $userId=null)
    {
        $model = $this->findModel($id);

        if ($userId != null) {
            // добавляем пользователя
            /* @var $auth \yii\rbac\DbManager */
            $auth = \Yii::$app->authManager;
            $role = $auth->getRole($model->name);
            $auth->assign($role, $userId);

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return 'OK';
                Yii::$app->end();
            }
            else {
                return $this->redirect(['/admin/role/admin', 'id' => $id]);
            }
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => 'Добавление пользователя',
                'content' => $this->renderAjax('addUser', [
                    'dataProvider' => $model->getUsersForAddRole(),
                    'model' => $model,
                ]),
            ];
        }

        return $this->render('addUser', [
            'dataProvider' => $model->getUsersForAddRole(),
            'model' => $model,
        ]);
    }

    /**
     * Удаление роли у пользователя
     * @param $id идентификатор роли
     * @param $userId идентификатор пользователя
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDeleteSubUser($id, $userId)
    {
        $model = $this->findModel($id);
        /* @var $auth \yii\rbac\DbManager */
        $auth = \Yii::$app->authManager;
        $role = $auth->getRole($model->name);
        $auth->revoke($role, $userId);

        return $this->redirect(['/admin/role/admin', 'id'=>$id]);
    }

    // End: Sub users


    // Sub roles

    public function actionAddSubRole($id, $roleId=null)
    {
        $model = $this->findModel($id);

        if ($roleId != null) {
            // добавляем пользователя
            /* @var $auth \yii\rbac\DbManager */
            $auth = \Yii::$app->authManager;
            $role = $auth->getRole($model->name);
            $roleChild = $auth->getRole($roleId);
            $auth->addChild($role, $roleChild);

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return 'OK';
                Yii::$app->end();
            }
            else {
                return $this->redirect(['/admin/role/admin', 'id'=>$id]);
            }
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => 'Добавление роли',
                'content' => $this->renderAjax('addRole', [
                    'dataProvider' => $model->getRolesForAddRole(),
                    'model' => $model,
                ]),
            ];
        }

        return $this->render('addRole', [
            'dataProvider' => $model->getRolesForAddRole(),
            'model' => $model,
        ]);
    }

    /**
     * Удаление дочерней роли
     * @param $id текущая роль (из которой нужно удалить)
     * @param $roleId роль, которую нужно удалить
     * @return \yii\web\Response
     */
    public function actionDeleteSubRole($id, $roleId)
    {
        /* @var $auth \yii\rbac\DbManager */
        $auth = \Yii::$app->authManager;
        $parent = $auth->getRole($id);
        $child = $auth->getRole($roleId);
        $auth->removeChild($parent, $child);
        return $this->redirect(['/admin/role/admin', 'id'=>$id]);
    }

    // End: Sub roles

    /**
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Role the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Role::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * @return array
     */
    private function getPaginzationSize()
    {
        return isset(\Yii::$app->params['role']['pageSize']) 
            ? \Yii::$app->params['role']['pageSize'] : \Yii::$app->params['pageSize'];
    }

    
}
