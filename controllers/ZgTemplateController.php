<?php

namespace app\controllers;

use app\components\UserInfo;
use Yii;
use app\models\zg\ZgTemplate;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ZgTemplateController implements the CRUD actions for ZgTemplate model.
 */
class ZgTemplateController extends Controller
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
     * Lists all ZgTemplate models.
     * @return mixed
     */
    public function actionIndex($kind=null)
    {
        $query = ZgTemplate::find();
        if ($kind!=null) {
            $query->where(['kind' => $kind]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->getPageSize(),
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'kind' => $kind,
            'kindList' => $this->getKindList(),
            'isEditor' => $this->isEditor(),
        ]);
    }

    /**
     * @return array
     */
    private function getKindList()
    {
        $query = new Query();
        $resultQuery = $query->from('{{%zg_template_kind}}')
            ->orderBy(['kind_name' => SORT_ASC])
            ->all();
        return ArrayHelper::map($resultQuery, 'kind_name', 'kind_name');
    }

    /**
     * Количество строк при выводе информации по базе адресов
     * @return mixed
     */
    private function getPageSize()
    {
        return Yii::$app->params['zg']['template']['pageSize'];
    }

    /**
     * Displays a single ZgTemplate model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'isEditor' => $this->isEditor(),
        ]);
    }

    /**
     * Creates a new ZgTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ZgTemplate();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ZgTemplate model.
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
     * Deletes an existing ZgTemplate model.
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
     * Finds the ZgTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ZgTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ZgTemplate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Есть ли права на редактирование
     * @return bool
     */
    protected function isEditor()
    {
        // если права администратора
        if (Yii::$app->user->can('admin')) {
            //return true;
        }

        if (Yii::$app->user->isGuest) {
            return false;
        }

        $accounts = Yii::$app->params['zg']['template']['editAccounts'];

        // поиск по имени учетной записи
        if (in_array(Yii::$app->user->identity->username, $accounts)) {
            return true;
        }

        $members = Yii::$app->userInfo->ADMemberOf;
        if (is_array($members) && count($members)) {
            // поиск по группам
            foreach ($accounts as $account) {
                if (in_array($account, $members)) {
                    return true;
                }
            }
        }

        return false;
    }
}
