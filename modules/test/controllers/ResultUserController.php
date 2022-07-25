<?php

namespace app\modules\test\controllers;

use app\modules\test\models\Test;
use yii\db\Query;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\modules\test\models\TestResult;
use app\modules\test\models\TestResultOpinion;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

/**
 * ResultUserController controller for the `test` module
 */
class ResultUserController extends Controller
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
                        'roles' => ['admin', 'test-statistic'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($id)
    {
        $model = $this->findModel($id);
               
        \Yii::$app->response->format = Response::FORMAT_JSON;
        
        $dataProvider = new ActiveDataProvider([
            'query' => TestResult::find()->where([
                'id_test' => $id,                
            ]),
        ]);

        return [
            'title' => 'Результаты теста "' . $model->name . '"',
            'body' => $this->renderAjax('index', [
                'dataProvider' => $dataProvider,
            ]),
        ];
        
    }


    /**
     * Статистика теста
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionStatistic($id)
    {
        $model = $this->findModel($id);
        if (!$model->isViewStatistic()) {
            throw new ForbiddenHttpException();
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => 'Статистика теста "' . $model->name . '"',
            'content' => $this->renderAjax('_statistic', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Общая статистика 
     * @param int $id
     */
    public function actionGeneral($id)
    {
        $model = $this->findModel($id);
        
        $query = (new Query())
            ->from('{{%test_result}} t')
            ->rightJoin('{{%organization}} org', 't.org_code=org.code')
            ->leftJoin('{{%test_result_question}} q', 't.id=q.id_test_result')
            ->where(['t.id_test' => $id])
            ->groupBy('t.org_code, org.name')
            ->orderBy('t.org_code')
            ->select('t.org_code, org.name, count(distinct t.id) count_test, count(q.id) count_question, sum(cast(q.is_right as smallint)) count_right')
            ->all();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'sort' => [
                'attributes' => ['org_code' => SORT_ASC],
            ],
            'pagination' => false,
        ]);

        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Общая статистика теста {$model->name}",
                'body' => $this->renderAjax('general', [
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                ])
            ];
        }
        return $this->render('general', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     */
    public function actionUsers($id)
    {
        $model = $this->findModel($id);

        $query = (new Query())
            ->from('{{%test_result}} t')
            ->leftJoin('{{%organization}} org', 't.org_code=org.code')
            ->select('org.code, org.name')
            ->distinct(true)
            ->orderBy(['org.code' => SORT_ASC])
            ->all();

        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Статистика по сотрудникам {$model->name}",
                'body' => $this->renderAjax('users', [
                    'query' => $query,
                    'model' => $model,
                ])
            ];
        }
        return $this->render('users', [
            'query' => $query,
            'model' => $model,
        ]); 
    }

    /**
     * @param $id
     * @param string $orgCode
     * @return string
     */
    public function actionUsersDetail($id, $orgCode)
    {
        $model = $this->findModel($id);
        
        $query = (new Query())
            ->from('{{%test_result}} test_result')
            ->select("
                test_result.username
                ,u.fio
                ,test_result.id
                ,test_result.date_create
                ,test_result.status
                ,count(DISTINCT test_result_question.id) count_question
                ,count(DISTINCT test_result_question_right.id) count_right
            ")
            ->leftJoin('{{%test_result_question}} test_result_question', 'test_result_question.id_test_result=test_result.id')
            ->leftJoin('{{%test_result_question}} test_result_question_right', 'test_result_question_right.id_test_result=test_result.id and test_result_question_right.is_right=1')
            ->leftJoin('{{%user}} u', 'u.username_windows=test_result.username')
            ->where('test_result_question.id is not null')
            ->andWhere([
                'test_result.id_test' => $id,
                'test_result.org_code' => $orgCode,
            ])
            ->andWhere(['not', ['test_result.status' => TestResult::STATUS_START]])
            ->groupBy("test_result.username, u.fio, test_result.id, test_result.date_create, test_result.status")
            ->indexBy('id')
            ->all();
        

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'sort' => [
                'attributes' => ['u.fio' => SORT_ASC],
            ],
            'pagination' => false,
        ]);        

        if (Yii::$app->request->isAjax) {           
            return $this->renderAjax('users-detail', [
                'dataProvider' => $dataProvider,
                'model' => $model,
                'orgCode' => $orgCode,
            ]);
        }
        return $this->render('users-detail', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'orgCode' => $orgCode,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionQuestions($id)
    {
        $model = $this->findModel($id);

        $qurey = "
            select * from 
            (
                select 
                      test_question.name
                     ,test_question.id
                     ,(select count(*) from p_test_result_question t where t.id_test_question=test_question.id) count_answered
                     ,(select count(*) from p_test_result_question t where t.id_test_question=test_question.id and t.is_right=1) count_right
                from {{%test_question}} test_question	
                where test_question.id_test=:id
            ) as t
            order by t.count_right asc";

        $result = \Yii::$app->db->createCommand($qurey)
            ->bindParam(':id', $id)
            ->queryAll();
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $result,            
            'pagination' => false,            
        ]);

        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Статистика по вопросам {$model->name}",
                'body' => $this->renderAjax('questions', [
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                ])
            ];
        }
        return $this->render('questions', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * @return string
     */
    public function actionQuestionsAjax()
    {
        if (isset($_POST['expandRowKey']) && is_numeric($_POST['expandRowKey'])) {
            $idQuestion = $_POST['expandRowKey'];
            $query = (new Query())
                ->from('{{%test_result}} test_result')
                ->select("
                     test_result.org_code
                    ,org.name
                    ,count(distinct test_result_question.id) count_all
                    ,count(distinct test_result_question_right.id) count_right
                ")
                ->leftJoin('{{%test_result_question}} test_result_question', 'test_result_question.id_test_result = test_result.id')
                ->leftJoin('{{%test_result_question}} test_result_question_right', 'test_result_question_right.id = test_result_question.id 
                    and test_result_question_right.is_right = 1')
                ->leftJoin('{{%organization}} org', 'org.code = test_result.org_code')
                ->where('test_result_question.id_test_question=:idQuestion', [':idQuestion'=>$idQuestion])
                ->groupBy('test_result.org_code, org.name')
                ->orderBy('(count(distinct test_result_question_right.id) - count(distinct test_result_question.id)) asc')
                ->all();
            
            $dataProvider = new ArrayDataProvider([
                'allModels' => $query,
                'pagination' => false,
            ]);
            
            return $this->renderAjax('questions-detail', [
                'dataProvider' => $dataProvider, 
                'idQuestion' => $idQuestion,               
            ]);
        }
        else {
            return '<div class="alert alert-info">Данных не найдено!</div>';
        }
    }

    /**
     * Просмотр результатов оценки
     * @return string
     */
    public function actionOpinion($id)
    {
        $model = $this->findModel($id);
        
        $query = (new Query())
            ->from('{{%test_result_opinion}} test_result_opinion')
            ->select("                
                 test_result_opinion.id
                ,test_result_opinion.rating
                ,test_result_opinion.note
                ,test_result_opinion.author
                ,u.fio                
                ,test_result_opinion.date_create                
            ")
            ->leftJoin('{{%user}} u', 'test_result_opinion.author = u.username')            
            ->andWhere([
                'test_result_opinion.id_test' => $id,                
            ])            
            ->orderBy(['test_result_opinion.date_create' => SORT_ASC])
            ->all();
        

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'sort' => [
                'attributes' => ['u.fio' => SORT_ASC],
            ],
            'pagination' => false,
        ]);        

        if (Yii::$app->request->isAjax) {  
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Статистика по вопросам {$model->name}",
                'body' => $this->renderAjax('opinion', [
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                ]),
            ];                     
        }
        return $this->render('opinion', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }








    /**
     * @param $idQuestion
     * @return string
     *//*
    public function actionStatisticAnswersDetail($idQuestion)
    {
        $query = new Query();
        $resultQuery = $query
            ->from('{{%test_result}} test_result')
            ->select("
                 test_result.org_code
                ,count(DISTINCT test_result_question.id) count_all
                ,count(DISTINCT test_result_question_right.id) count_right
            ")
            ->leftJoin('{{%test_result_question}} test_result_question', 'test_result_question.id_test_result = test_result.id')
            ->leftJoin('{{%test_result_question}} test_result_question_right', 'test_result_question_right.id = test_result_question.id 
                and test_result_question_right.is_right = 1')
            ->where('test_result_question.id_test_question=:idQuestion', [':idQuestion'=>$idQuestion])
            ->groupBy('test_result.org_code')
            ->orderBy('(count(DISTINCT test_result_question_right.id) - count(DISTINCT test_result_question.id)) asc')
            ->all();
        return $this->renderAjax('_statisticAnswersDetail', [
            'resultQuery' => $resultQuery,
        ]);
    }*/

    /**
     * Finds the Test model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Test the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Test::findOne($id)) !== null) {
            if (!$model->canStatisticTest()) {
                throw new ForbiddenHttpException('Access denied to this model');
            }
            return $model;
        }        
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Find the TestResult model
     */
    protected function findModelTestResult(int $id)
    {
        return TestResult::findOne($id);
    }

    /**
     * Поиск результата текущего пользователя
     * Если не найден результат, то создание его
     * @return TestResult
     */
    /*
    protected function findOrCreateTestResultCurrentUser(int $idTest)
    {
        $query = TestResult::find()->where([
            'id_test' => $idTest,
            'username' => \Yii::$app->user->identity->username,
        ])
        ->andWhere(['status' => TestResult::STATUS_START])
        ->orderBy(['id' => SORT_DESC])
        ->one();

        if ($query === null) {
            $query = new TestResult([
                'id_test' => $idTest,
                'username' => \Yii::$app->user->identity->username,
                'org_code' => \Yii::$app->userInfo->current_organization,
                'status' => TestResult::STATUS_START,
                'seconds' => 0,
                'date_create' => \Yii::$app->formatter->asDatetime(time()),
            ]);            
            if (!$query->save()) {
                throw new ServerErrorHttpException('Ошибка при создании результатов тестов!');
            }
            $query->generateNewTest();
        }
        return $query;
    }    
    */

    /**
     * @param int $rating
     * @param string $note
     */
    /*
    public function actionRating($id)
    {
        $modelTest = $this->findModel($id);
        $post = \Yii::$app->request->post();
        $rating = $post['rating'] ?? null;
        if ($rating == null) {
            throw new HttpException(500, 'Не выбрано ни одной звезды');
        }
        $note = $post['note'] ?? null;
        if ($rating != null && is_numeric($rating)) {
            $model = new TestResultOpinion([
                'id_test' => $modelTest->id,
                'rating' => $rating,
                'note' => $note,
            ]);
            $model->save();
        }        
    }
    */
}
