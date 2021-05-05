<?php

namespace app\controllers;

use app\models\vote\VoteNewyearToy;
use app\models\vote\VoteNewyearToyAnswer;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * Class NewYearToyController
 * @package app\controllers
 */
class NewYearToyController extends \yii\web\Controller
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
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    /**
     * Главная (список вопросов)
     * @return string
     */
    public function actionIndex()
    {
        $model = VoteNewyearToy::find()
            ->alias('t')
            ->orderBy('(select count(id) from {{%vote_newyear_toy_answer}} where id_vote_newyear_toy = t.id) desc')
            ->all();
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionStatistic($id)
    {
        if (!VoteNewyearToy::showBtnStatistic()) {
            throw new ForbiddenHttpException();
        }
        $model = $this->findModel($id);

        if (\Yii::$app->request->isAjax) {
            $resultJson = [
                'title'=>$model->name . '<br /><small>' . $model->department . '</small>',
                'content' => $this->renderAjax('statistic', [
                    'model' => $model,
                    'dataProvider' => $model->voteResults(),
                ]),
            ];
            return Json::encode($resultJson);
        }
        else {
            return $this->render('statistic', [
                'model' => $model,
                'dataProvider' => $model->voteResults(),
            ]);
        }
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionVote($id)
    {
        $model = $this->findModel($id);
        $modelAnswer = new VoteNewyearToyAnswer();
        $modelAnswer->id_vote_newyear_toy = $model->id;
        $modelAnswer->save();
        return 'OK';
    }

    /**
     * @param $id
     * @return VoteNewyearToy|null
     * @throws NotFoundHttpException
     */
    private function findModel($id)
    {
        if (($model = VoteNewyearToy::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
