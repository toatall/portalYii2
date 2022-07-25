<?php

namespace app\controllers;

use app\models\vote\VoteMain;
use app\models\vote\VoteQuestion;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use app\components\Controller;

/**
 * Class VoteController
 * @package app\controllers
 */
class VoteController extends Controller
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
    public function actionIndex($id)
    {
        $model = $this->findModel($id);

        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax('vote', [
                'model' => $model,
            ]);
        }

        return $this->render('vote', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws HttpException
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionVoted($id)
    {
        if (!\Yii::$app->request->isPost) {
            throw new HttpException(500, 'Allows only post request. You must send post request');
        }

        $model = $this->findModelQuestion($id);

        // закончилось голосование?
        if ($model->main->getEndVote()) {
            throw new HttpException(500, 'Голосование завершено');
        }

        // проверить остались ли еще попытки
        if ($model->main->isCountVoteEnd()) {
            throw new HttpException(500, 'Вы использовали все Ваши голоса!');
        }

        // проголосовал ли уже
        if ($model->isVoted()) {
            throw new HttpException(500, 'Вы уже проголосовали!');
        }

        // сохранение голосования
        \Yii::$app->db->createCommand()
            ->insert('{{%vote_answer}}', [
                'id_question' => $id,
                'username' => \Yii::$app->user->identity->username,
            ])
            ->execute();

        // обновления количества
        \Yii::$app->db->createCommand("
            update {{%vote_question}} 
                set count_votes = (select count(id) from {{%vote_answer}} where id_question=:id1)
            where id=:id2            
        ")
        ->bindValue(':id1', $id)
        ->bindValue(':id2', $id)
        ->execute();

        return (new Query())
            ->from('{{%vote_answer}}')
            ->where([
                'id_question' => $id,
            ])
            ->count('id');
    }

    /**
     * @param $id
     * @return VoteMain|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = VoteMain::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $id
     * @return VoteQuestion|null
     * @throws NotFoundHttpException
     */
    protected function findModelQuestion($id)
    {
        if (($model = VoteQuestion::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }


}
