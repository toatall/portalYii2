<?php

namespace app\modules\kadry\controllers;

use app\modules\kadry\models\Award;
use Yii;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class AwardController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [                       
                        'allow' => true,
                        'roles' => ['admin'],
                    ],           
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => [Award::roleReader(), Award::roleModerator()],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => [Award::roleModerator()],
                    ],
                ],
            ],           
        ];
    }
    

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {       
        $searchModel = new Award();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [           
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionView($id)
    {       
        $model = $this->findModel($id);
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => 'Просмотр записи ' . $model->fio,
            'content' => $this->renderAjax('view', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Добавление данных о сотруднике и(или) награде
     * @param string $org
     * @return string
     */
    public function actionCreate($org)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new Award();
        $model->scenario = 'create-update';
        $model->org_code = $org;
        $model->flag_dks = 0;    
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return ['content' => 'ok'];
        }
        
        return [
            'title' => 'Добавление награды',
            'content' => $this->renderAjax('form', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Изменение данных о сотруднике и(или) награде
     * @param string $id
     * @return string
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {          
            return ['content' => 'ok'];
        }

        return [
            'title' => 'Изменение записи ' . $model->fio,
            'content' => $this->renderAjax('form', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Deletes an existing Award model.   
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete() == false) {
            throw new ServerErrorHttpException('Error deletion item!');
        }        
        return 'OK';
    }


    /**
     * Finds the Award model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Award the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Award::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /**
     * Поиск сотрудника по ФИО
     * @param string $q
     * @param string $org
     * @return array
     */
    public function actionListFio($q, $org)
    {
        $query = (new Query())
            ->from(Award::tableName())
            ->where(['like', 'fio', $q])
            ->andWhere(['org_code' => $org])
            ->select('fio, dep_index, dep_name, post')
            ->distinct(true)
            ->all(Award::getDb());
        
        $res = [];
        foreach($query as $item) {
            $res[] = [
                'value' => $item['fio'],
                'dep_index' => $item['dep_index'],
                'dep_name' => $item['dep_name'],
                'post' => $item['post'],
            ];
        }
        return Json::encode($res);
    }
    
    /**
     * Поиск наград
     * @param string $q
     * @param string $org
     * @return array
     */
    public function actionListAwards($q, $org)
    {
        $query = (new Query())
            ->from(Award::tableName())
            ->where(['like', 'aw_name', $q])
            ->andWhere(['org_code' => $org])
            ->select('aw_name, aw_doc, aw_doc_num, aw_date_doc')
            ->distinct(true)
            ->all(Award::getDb());
        
        $res = [];
        foreach($query as $item) {
            $res[] = [
                'value' => trim($item['aw_name']),
                'aw_doc' => $item['aw_doc'],
                'aw_doc_num' => $item['aw_doc_num'],
                'aw_date_doc' => Yii::$app->formatter->asDate($item['aw_date_doc']),
            ];
        }
        return Json::encode($res);
    }


}
