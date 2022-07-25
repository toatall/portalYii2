<?php

namespace app\modules\test\controllers;

use Yii;
use app\modules\test\models\TestAnswer;
use yii\data\ActiveDataProvider;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\modules\test\models\TestQuestion;

/**
 * AnswerController implements the CRUD actions for TestAnswer model.
 */
class AnswerController extends Controller
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
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TestAnswer models.
     * @return mixed
     */
    public function actionIndex($idQuestion)
    {
        $modelQuestion = $this->findModelQuestion($idQuestion);
        $dataProvider = new ActiveDataProvider([
            'query' => TestAnswer::find()->where(['id_test_question'=>$idQuestion]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'modelQuestion' => $modelQuestion,
        ]);
    }

    /**
     * Displays a single TestAnswer model.
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
     * Creates a new TestAnswer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idQuestion)
    {
        $modelQuestion = $this->findModelQuestion($idQuestion);
        $model = new TestAnswer();
        $model->id_test_question = $idQuestion;
        $model->weight = 0;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'idQuestion' => $model->id_test_question]);
        }

        return $this->render('create', [
            'model' => $model,
            'modelQuestion' => $modelQuestion,
        ]);
    }

    /**
     * Updates an existing TestAnswer model.
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
     * Deletes an existing TestAnswer model.
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
     * Finds the TestAnswer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TestAnswer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TestAnswer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * Finds the TestQuestion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param $id
     * @return TestQuestion|null
     * @throws NotFoundHttpException
     */
    protected function findModelQuestion($id)
    {
        if (($model = TestQuestion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The request page does not exist.');
    }
}
