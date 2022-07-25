<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\vote\VoteMain;
use app\models\vote\VoteQuestion;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * VoteController implements the CRUD actions for VoteMain model.
 */
class VoteController extends Controller
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
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all VoteMain models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => VoteMain::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VoteMain model.
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
     * Creates a new VoteMain model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VoteMain();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing VoteMain model.
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
     * Deletes an existing VoteMain model.
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
     * Finds the VoteMain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VoteMain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VoteMain::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    // VOTE QUESTION

    /**
     * Управление вопросами
     * @param integer $idMain идентификатор голосования
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndexQuestion($idMain)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => VoteQuestion::find()->where(['id_main'=>$idMain]),
        ]);

        return $this->render('indexQuestion', [
            'dataProvider' => $dataProvider,
            'modelVoteMain' => $this->findModel($idMain),
        ]);
    }

    /**
     * Displays a single VoteQuestion model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewQuestion($id)
    {
        return $this->render('viewQuestion', [
            'model' => $this->findModelQuestion($id),
        ]);
    }

    /**
     * Creates a new VoteMain model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCreateQuestion($idMain)
    {
        $model = new VoteQuestion();
        $model->id_main = $idMain;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view-question', 'id' => $model->id]);
        }

        return $this->render('createQuestion', [
            'model' => $model,
            'modelVoteMain' => $this->findModel($idMain),
        ]);
    }

    /**
     * Updates an existing VoteMain model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateQuestion($id)
    {
        $model = $this->findModelQuestion($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view-question', 'id' => $model->id]);
        }

        return $this->render('updateQuestion', [
            'model' => $model,
            'modelVoteMain' => $this->findModel($model->id_main),
        ]);
    }

    /**
     * Deletes an existing VoteMain model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteQuestion($id)
    {
        $this->findModelQuestion($id)->delete();

        return $this->redirect(['index-question']);
    }

    /**
     * Finds the VoteMain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VoteQuestion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelQuestion($id)
    {
        if (($model = VoteQuestion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


}
