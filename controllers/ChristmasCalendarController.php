<?php

namespace app\controllers;

use app\models\christmascalendar\ChristmasCalendar;
use app\models\christmascalendar\ChristmasCalendarQuestion;
use app\models\news\NewsSearch;
use app\models\page\PageSearch;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\Controller;

/**
 * Class CrismasCalendarController
 * @package app\controllers
 */
class ChristmasCalendarController extends Controller
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
     * Главная страница
     * @return string
     * @throws \Exception
     */
    public function actionIndex()
    {
        $model = new ChristmasCalendar();
        $users = (new Query())
            ->from('{{%christmas_calendar_users}}')
            ->orderBy(['fio'=>SORT_ASC])
            ->all();

        return $this->render('index', [
            'model' => $model,
            'data' => $model->allDays(),
            'today' => $model->today(),
            'listUsers' => ArrayHelper::map($users, 'id', 'fio'),
        ]);
    }

    /**
     * @return string
     * @throws HttpException
     * @throws \Exception
     */
    public function actionGuess()
    {
        $post = \Yii::$app->request->post();
        if (!isset($post['answer'])) {
            throw new HttpException(500, 'Не передан параметр answer');
        }

        $idAnswer = $post['answer'];
        if (!is_numeric($idAnswer) || !$this->checkUserOnList($idAnswer)) {
            throw new HttpException(500, 'Не найден сотрудник по такому идентификатору');
        }

        $modelQuestion = $this->findQuestion();
        if ($modelQuestion == null) {
            throw new HttpException(500, 'Вопрос за текущий день не найден');
        }

        $modelQuestion->saveAnswer($idAnswer);
    }

    /**
     * @param $day integer
     * @return string
     * @throws \yii\db\Exception
     * @throws NotFoundHttpException
     */
    public function actionStatistic($day)
    {
        $query = "
            select distinct 
                 question.photo
                ,question.description
                ,users.fio
                ,users.department
                ,(select count(id) from {{%christmas_calendar_answer}} answer where answer.id_question=question.id) count_all
                ,(select count(id) from {{%christmas_calendar_answer}} answer where answer.id_question=question.id and question.id_user=answer.id_user) count_right
            from {{%christmas_calendar_question}} question
                left join {{%christmas_calendar_users}} users on question.id_user=users.id
            where question.day=:day
        ";
        $resultQuery = \Yii::$app->db->createCommand($query)
            ->bindValue(':day', $day)
            ->queryOne();


        $queryWrong = "
            select 
                 usr.fio
                ,count(answer.id) answers
            from [dbo].[p_christmas_calendar_question] question
                left join [dbo].[p_christmas_calendar_answer] answer on question.id = answer.id_question
                left join [dbo].[p_christmas_calendar_users] usr on answer.id_user = usr.id
            where question.day=:day and question.id_user<>answer.id_user
            group by usr.fio
            order by count(answer.id) desc
        ";
        $resultQueryWrong = \Yii::$app->db->createCommand($queryWrong)
            ->bindValue(':day', $day)
            ->queryAll();

        if ($resultQuery==null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => $resultQuery['fio'] . ' <br /><small>' . $resultQuery['department'] . '</small>',
            'content' => $this->renderAjax('statistic', [
                'data' => $resultQuery,
                'wrong' => $resultQueryWrong,
            ]),
        ];
    }

    /**
     * @return ChristmasCalendarQuestion|null
     * @throws \Exception
     */
    protected function findQuestion()
    {
        return ChristmasCalendarQuestion::findToday();
    }

    /**
     * Проверка, есть ли такой сотрудник?
     * @param $id
     * @return bool
     */
    protected function checkUserOnList($id)
    {
        return (new Query())
            ->from('{{%christmas_calendar_users}}')
            ->where([
                'id' => $id,
            ])
            ->exists();
    }

}
