<?php

namespace app\modules\test\controllers;

use app\modules\test\models\Test;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\test\models\TestResult;
use app\modules\test\models\TestResultAnswer;
use app\modules\test\models\TestResultOpinion;
use app\modules\test\models\TestResultQuestion;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

/**
 * Public controller for the `test` module
 */
class PublicController extends Controller
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
        return $this->render('/test/index', [            
            'dataProvider' => new ActiveDataProvider([
                'query' => Test::find()->where(['id' => $id]),
            ])
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
        
        // проверка 
        $check = $this->checkStatuses($model);
        if ($check) {
            return $check;
        }

        // если пользователь уже ранее сдавал тесты, то подгружаем результаты
        $modelResult = $this->findOrCreateTestResultCurrentUser($id);
        
        // передача post-данных при завершении теста
        if (Yii::$app->request->post('Test') !== null) {           
            $this->saveResult(Yii::$app->request->post('Test'), $modelResult);
            return $this->redirect(['/test/result/view', 'id'=>$modelResult->id]);
        }

        // если пройденое количество секунд превышает лимитированное количество,
        // то сохраняем с результатом, что тест завершен
        $limitSeconds = $model->getTimeLimitSeconds();
        if ($limitSeconds > 0 && $modelResult->seconds >= $limitSeconds) {
            $this->saveResult(null, $modelResult);
            return $this->redirect(['/test/result/view', 'id'=>$modelResult->id]);
        }
        
        if ($model->show_right_answer) {
            return $this->render('startHighlightAnswer', [
                'model' => $model,
                'modelResult' => $modelResult,          
            ]);
        }
        else {
            return $this->render('start', [
                'model' => $model,
                'modelResult' => $modelResult,          
            ]);
        }
    }

    /**
     * @todo move to Quiz module
     * Выдать следующий вопрос
     */
    public function actionHighlightAnswer(int $idResult)
    {
        $model = $this->findTestResultCurrentUser($idResult);
        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $modelQuestion = $model->getNextQuestion();
        if ($modelQuestion == null) {           
            return 'finish';
        }

        return $this->renderAjax('_questionHighlightAnswer', [
            'model' => $modelQuestion,
        ]);
    }

    
    public function actionFinish(int $idResult)
    {
        $model = $this->findTestResultCurrentUser($idResult);
        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model->status = TestResult::STATUS_FINISH;
        $model->save();
        return $this->redirect(['/test/result/view', 'id'=>$model->id]);
    }

    /**
     * @todo move quiz module
     */
    public function actionPartialSaveHighlightAnswer(int $idResult, int $idQuestion, int $idAnswer)
    {
        // проверка, есть ли такой результат и принадлежит ли он текущему пользователю
        $model = $this->findTestResultCurrentUser($idResult);
        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        // поиск вопроса
        /** @var TestResultQuestion $modelQuestion */
        $modelQuestion = $model->getTestResultQuestions()->where(['id_test_question' => $idQuestion])->one();
        if ($modelQuestion == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        // если ответы уже есть то выдаем ошибку
        if ($modelQuestion->getTestResultAnswers()->count() > 0) {
            throw new ServerErrorHttpException('Ответ уже был дан ранее');
        }

        $modelAnswer = new TestResultAnswer([
            'id_test_result_question' => $modelQuestion->id,
            'id_test_answer' => $idAnswer,
        ]);
        $modelAnswer->save();
        $modelQuestion->link('testResultAnswers', $modelAnswer);
        $modelQuestion->checkRightAnswer();

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'is_right' => $modelQuestion->is_right,
            'right_answer' => $modelQuestion->getRightAnswerId(),
            'question_name' => $modelQuestion->testQuestion->name,
        ];
    }

    
    /**
     * @param Test $model
     * @param TestResult $modelResult
     */
    private function saveResult($data, $modelResult)
    {
        if (is_array($data) && !empty($data)) {
            // 1. Удаление всех ранее данных ответов
            $modelResult->deleteAnswers();
            
            // 2. Сохранение новых ответов
            $modelResult->saveAnswers($data);
        }

        // 3. Изменение статуса на завершен
        $modelResult->status = TestResult::STATUS_FINISH;
        $modelResult->save();
    }

    /**
     * Проверка органичений теста
     * 1. Тест еще не начался
     * 2. Тест уже закончился
     * 3. Количество попыток сдачи теста закончено 
     */
    private function checkStatuses(Test $model)
    {
        $status = $model->processStatus();
        $message = null;
        if ($status == Test::PROCESS_STATUS_NOT_START) {
            $message = 'Тестирование начнется ' . \Yii::$app->formatter->asDatetime($model->date_start) . '!';
        }
        if ($status == Test::PROCESS_STATUS_FINISHED) {
            $message = 'Тестирование завершено ' . \Yii::$app->formatter->asDatetime($model->date_end) . '!';
        } 
        
        // Если ограничено количество попыток и пользователь уже истратил все свои попытки
        $countUserAttempts = TestResult::countUserAttempts($model->id);
        if ($model->count_attempt > 0 && $countUserAttempts >= $model->count_attempt) {
           $message = 'Количество попыток закончилось!';
        }        

        if ($message !== null) {
            return $this->render('message', [
                'model' => $model,
                'message' => $message,                
            ]);
        }
        return false;
    }

    /**
     * Промежуточное сохранение времени сдачи теста
     * Для восстановления, в случае прерывания
     */
    public function actionPartialSetTimeout(int $id, int $seconds)    
    {        
        $model = $this->findModelTestResult($id);
        if ($model == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        // добавление секунд
        $model->seconds += $seconds;       
        return $model->save();
    }

    /**
     * Промежуточное сохранение ответов
     * Для восстановления, в случае прерывания
     * @param int $idResult
     * @param int $idQuestion
     */
    public function actionPartialSaveAnswer(int $idResult, int $idQuestion)
    {
        // проверка, есть ли такой результат и принадлежит ли он текущему пользователю
        $model = $this->findTestResultCurrentUser($idResult);
        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        // поиск вопроса
        /** @var TestResultQuestion $modelQuestion */
        $modelQuestion = $model->getTestResultQuestions()->where(['id_test_question' => $idQuestion])->one();
        if ($modelQuestion == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        
        // удаление ответов
        $modelQuestion->unlinkAll('testResultAnswers', true);

        // получение переданных данных
        $post = Yii::$app->request->post();
        if (!isset($post['Test'][$idQuestion])) {
            return;
        }
        $postData = $post['Test'][$idQuestion]; 
        if (!is_array($postData)) {
            $postData = [$postData];
        }

        // запись новых ответов
        foreach ($postData as $data) {
            $modelAnswer = new TestResultAnswer([
                'id_test_result_question' => $modelQuestion->id,
                'id_test_answer' => $data,
            ]);
            $modelAnswer->save();
            $modelQuestion->link('testResultAnswers', $modelAnswer);
            $modelQuestion->checkRightAnswer();
        }        
    }


    /**
     * @param int $rating
     * @param string $note
     */
    public function actionRating($id)
    {
        $model = $this->findModel($id);
        if ($model == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $modelOpiniton = $this->findModelOpinionCurrentUser($id);        
        $post = \Yii::$app->request->post();
        $rating = $post['rating'] ?? null;
        if ($rating != null) {            
            $note = $post['note'] ?? null;
            if ($rating != null && is_numeric($rating)) {
                if ($modelOpiniton === null) {
                    $modelOpiniton = new TestResultOpinion();
                }
                $modelOpiniton->id_test = $model->id;
                $modelOpiniton->rating = $rating;
                $modelOpiniton->note = $note;
                $modelOpiniton->author = Yii::$app->user->identity->username;
                $modelOpiniton->date_create = Yii::$app->formatter->asDatetime('now');
                $model->save();
            }
        }
        elseif (Yii::$app->request->isAjax) {
            throw new ServerErrorHttpException('Не выбрано ни одной звезды');
        }
        return $this->render('rating', [
            'model'=>$model,
            'modelOpinion'=>$modelOpiniton,
        ]);
    }


    /**
     * @return TestResult
     */
    protected function findTestResultCurrentUser(int $idTestResult)
    {
        return TestResult::find()->where([
            'id' => $idTestResult,
            'username' => \Yii::$app->user->identity->username,
        ])
        ->andWhere(['status' => TestResult::STATUS_START])
        ->orderBy(['id' => SORT_DESC])
        ->one();
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
     * Find the TestResult model
     */
    protected function findModelTestResult(int $id)
    {
        return TestResult::findOne($id);        
    }

    protected function findModelOpinionCurrentUser($idTest)
    {
        return TestResultOpinion::find()->where([
            'id_test' => $idTest,
            'author' => Yii::$app->user->identity->username,
        ])->one();
    }

    /**
     * Поиск результата текущего пользователя
     * Если не найден результат, то создание его
     * @return TestResult
     */
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
    
    
}
