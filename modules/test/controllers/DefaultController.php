<?php

namespace app\modules\test\controllers;

use app\modules\test\models\Test;
use app\modules\test\models\TestQuestion;
use yii\db\Command;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\modules\test\models\TestResult;
use app\modules\test\models\TestResultOpinion;

/**
 * Default controller for the `test` module
 */
class DefaultController extends Controller
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
        $query = new Query();
        $result = $query->from('{{%test}}')
            ->all();

        return $this->render('index', [
            'result' => $result,
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
        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $model,
            ]);
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionStart($id)
    {
        /**
         * Логика по загрузке теста, вопросов и ответов
         * 0. Если прилетели post данные, то сохранить их и сказать спасибо!
         * 1. Загрузить тест в соотвествии с идентификатором, если время активности закончилось, то вывести информацию, тест закрыт
         * 2. Проверить сдавал ли пользователь ранее и можно ли еще сдавать
         * 3. Загрузить вопросы, ответы и передать массивом
         */
        $model = $this->findModel($id);

        // Если тест уже закончился, то показать об этом информацию
        if (!$model->getActive()) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => $model->name,
                'content' => $this->renderAjax('_message', [
                    'message' => 'Тестирование завершено!',
                ]),
            ];
        }

        // Если ограничено количество попыток и пользователь уже истратил все свои попытки
        $countUserAttempts = TestResult::countUserAttempts($id);
        if ($model->count_attempt > 0 && $countUserAttempts >= $model->count_attempt) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => $model->name,
                'content' => $this->renderAjax('_message', [
                    'message' => 'Количество попыток закончилось!',
                ]),
            ];
        }

        if (isset($_POST['Test'])) {
            // сохранение теста
            try {
                $modelResult = new TestResult();
                $result = $modelResult->saveResult($_POST['Test']);
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => $model->name,
                    'content' => $this->renderAjax('_result', [
                        'result' => $result,
                        'model' => $model,
                        'modelTestOpinion' => $this->findModelTestResultOpinion($id),
                    ]),
                ];
            }
            catch (\Exception $exception) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => $model->name,
                    'content' => $this->renderAjax('_message', [
                        'typeMessage' => 'alert-danger',
                        'message' => 'Во время сохранения произошли ошибки!<br />' . $exception->getMessage() . print_r($exception->getTrace(), true),
                    ]),
                ];
            }
        }

        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => $model->name,
                'content' => $this->renderAjax('start', [
                    'model' => $model,
                    'testData' => TestQuestion::searchQuestions($id),
                    'attempts' => [
                        'model' => $model->count_attempt,
                        'user' => $countUserAttempts,
                    ],
                ]),
            ];
        }
        else {
            return $this->render('start', [
                'model' => $model,
                'testData' => TestQuestion::searchQuestions($id),
                'attempts' => [
                    'model' => $model->count_attempt,
                    'user' => $countUserAttempts,
                ],
            ]);
        }
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
     * Вывод статистики тестирования
     * 1. По организациям: общее количество вопросов, правильное количество ответов и неправильное количество ответов
     * 2. По сотрудникам. При выборе организации выпадает список сотрудников, сдавших тест, по сотруднику можно посмотреть
     * на какие вопросы он ответил правильно/неправильно (какие дал ответы)
     * 3. Статистика по правильным/неправильным ответам
     * @param $id
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionStatisticOrganizations($id)
    {
        $model = $this->findModel($id);
        if (!$model->isViewStatistic()) {
            throw new ForbiddenHttpException();
        }
        $query = new Query();
        $resultQuery = $query->from('{{%test_result}} t')
            ->rightJoin('{{%organization}} org', 't.org_code=org.code')
            ->leftJoin('{{%test_result_question}} q', 't.id=q.id_test_result')
            ->where(['t.id_test' => $id])
            ->groupBy('t.org_code, org.name')
            ->orderBy('t.org_code')
            ->select('t.org_code, org.name, count(distinct t.id) count_test, count(q.id) count_question, sum(cast(q.is_right as smallint)) count_right')
            ->all();
        return $this->renderAjax('_statisticOrganizations', [
            'resultQuery' => $resultQuery,
        ]);
    }

    /**
     * @param $id
     * @param null $org
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionStatisticUsers($id, $org=null)
    {
        $model = $this->findModel($id);
        if (!$model->isViewStatistic()) {
            throw new ForbiddenHttpException();
        }
        if ($org != null) {
            $query = new Query();
            $resultQuery = $query->from('{{%test_result}} test_result')
                ->select("
                     test_result.username
                    ,u.fio
                    ,test_result.id
                    ,test_result.date_create
                    ,count(DISTINCT test_result_question.id) count_questions
                    ,count(DISTINCT test_result_question_right.id) count_right
                ")
                ->leftJoin('{{%test_result_question}} test_result_question', 'test_result_question.id_test_result=test_result.id')
                ->leftJoin('{{%test_result_question}} test_result_question_right', 'test_result_question_right.id_test_result=test_result.id and test_result_question_right.is_right=1')
                ->leftJoin('{{%user}} u', 'u.username_windows=test_result.username')
                ->where('test_result_question.id is not null')
                ->andWhere([
                    'test_result.id_test' => $id,
                    'test_result.org_code' => $org,
                ])
                ->groupBy("test_result.username, u.fio, test_result.id, test_result.date_create")
                ->all();
            return $this->renderAjax('_statisticUsersTable', [
                'model' => $model,
                'resultQuery' => $resultQuery,
            ]);
        }
        return $this->renderAjax('_statisticUsers', [
            'model' => $model,
        ]);
    }

    /**
     * @param $idTestResult
     * @param $userLogin
     * @return string
     */
    public function actionStatisticUserDetail($idTestResult, $userLogin)
    {
        $query = new Query();
        $resultQuery = $query->from('{{%test_result}} test_result')
            ->select('test_question.name, test_result_question.is_right')
            ->leftJoin('{{%test_result_question}} test_result_question', 'test_result_question.id_test_result=test_result.id')
            ->leftJoin('{{%test_question}} test_question', 'test_question.id=test_result_question.id_test_question')
            ->where([
                'test_result.id' => $idTestResult,
                'test_result.username'=>$userLogin,
            ])
            ->all();
        return $this->renderAjax('_statisticUserDetail', [
            'resultQuery' => $resultQuery,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionStatisticAnswers($id)
    {
        $model = $this->findModel($id);
        if (!$model->isViewStatistic()) {
            throw new ForbiddenHttpException();
        }
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
            order by t.count_right asc
        ";
        /* @var $command Command */
        $command = \Yii::$app->db->createCommand($qurey);
        $command->bindParam(':id', $id);
        return $this->renderAjax('_statisticAnswers', [
            'resultQuery' => $command->queryAll(),
        ]);
    }

    /**
     * @param $idQuestion
     * @return string
     */
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
    }

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
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /**
     * @param int $idTest
     * @return TestResultOpinion|null
     */
    protected function findModelTestResultOpinion($idTest)
    {
        return TestResultOpinion::find()
            ->where([
                'author' => \Yii::$app->user->identity->username,
                'id_test' => $idTest,
            ])
            ->one();
    }
    
    /**
     * @param int $rating
     * @param string $note
     */
    public function actionRating($id)
    {
        $modelTest = $this->findModel($id);
        $modelTestOpinion = $this->findModelTestResultOpinion($id);
        $post = \Yii::$app->request->post();
        $rating = $post['rating'] ?? null;
        $note = $post['note'] ?? null;
        if ($rating != null && is_numeric($rating)) {
            $model = new TestResultOpinion([
                'id_test' => $modelTest->id,
                'rating' => $rating,
                'note' => $note,
            ]);
            $model->save();
        }
        else {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => $modelTest->name,
                'content' => $this->renderAjax('rating', [
                    'modelTest' => $modelTest,
                    'modelTestOpinion' => $modelTestOpinion,
                ]),
            ];
        }
    }
}
