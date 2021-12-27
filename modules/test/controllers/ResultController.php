<?php

namespace app\modules\test\controllers;

use app\modules\test\models\TestResult;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;;
use yii\web\NotFoundHttpException;

/**
 * Result controller for the `test` module
 */
class ResultController extends Controller
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
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => TestResult::find()->where([
                'username' => Yii::$app->user->identity->username,                
            ]),
            //->andWhere(['in', 'status', [TestResult::STATUS_CANCEL, TestResult::STATUS_FINISH]]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);       
        return $this->render('view', [
            'model' => $model,
            'modelTest' => $model->test,
            'modelRating' => $model->getTestResultOpinion()->one(),
            'countWrong' => $model->getTestResultQuestions()->where('is_right=0 or is_right is null')->count(),
            'countRight' => $model->getTestResultQuestions()->where('is_right=1')->count(),
        ]);
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionViewAjax()
    {
        if (isset($_POST['expandRowKey']) && is_numeric($_POST['expandRowKey'])) {
            $model = $this->findModel($_POST['expandRowKey']);
            return $this->renderAjax('view', [
                'model' => $model,
                'modelTest' => $model->test,
                'modelRating' => $model->getTestResultOpinion()->one(),
                'countWrong' => $model->getTestResultQuestions()->where('is_right=0 or is_right is null')->count(),
                'countRight' => $model->getTestResultQuestions()->where('is_right=1')->count(),
                'statistsic' => true,
            ]); 
        }
        else {
            return '<div class="alert alert-info">Данных не найдено!</div>';
        }
    }

    /**
     * 
     */
    public function actionSaveChecked($id)    
    {
        $model = $this->findModel($id);
        foreach ($model->testResultQuestions as $question) {
            $question->is_right = Yii::$app->request->post("result_{$question->id}", 0);
            $question->save();
        }
    }

    /**
     * Finds the Test model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TestResult the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = TestResult::findOne($id);

        if ($model !== null) {

            // если это автор результата, то возвращаем результат
            if ($model->username === Yii::$app->user->identity->username) {
                return $model;
            }

            // либо если пользователь наделен правами просмотра результатов
            $modelTest = $model->test;
            if ($modelTest->canStatisticTest()) {
                return $model;
            }
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    
}
