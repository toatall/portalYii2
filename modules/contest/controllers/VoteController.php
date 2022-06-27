<?php

namespace app\modules\contest\controllers;

use app\modules\contest\models\VoteMain;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\db\Expression;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Vote controller for the `contest` module
 */
class VoteController extends Controller
{

    
    public $layout = '/portal';
   
    /**
     * {@inheritDoc}
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
    public function actionIndex($tag)
    {       
        $modelVoteMain = $this->findModelMainByTag($tag);

        return $this->render('index', [
            'modelVoteMain' => $modelVoteMain,
            'data' => $modelVoteMain->getVoteDataByNomination(),
        ]);
    }

    /**
     * Save current user's voice
     * @param [type] $id
     * @return void
     */
    public function actionSaveAnswer($id, $idMain)
    {
        $model = $this->findModelMain($idMain);
        if (!$model->isAuthorizeVote()) {
            throw new ServerErrorHttpException('Вы не авторизованы!');
        }
        if (!$model->isDateVote()) {
            throw new ServerErrorHttpException('Голосование завершено!');
        }
        $exists = (new Query())
            ->from('{{%contest_vote_answer}}')
            ->where([
                'id_contest_vote_data' => $id,
                'username' => Yii::$app->user->identity->username,                
            ])
            ->exists();
        if ($exists) {
            Yii::$app->db->createCommand()
                ->delete('{{%contest_vote_answer}}', [
                    'id_contest_vote_data' => $id,
                    'username' => Yii::$app->user->identity->username,                    
                ])
                ->execute();
        }
        else {
            Yii::$app->db->createCommand()
                ->insert('{{%contest_vote_answer}}', [
                    'id_contest_vote_data' => $id,
                    'username' => Yii::$app->user->identity->username,                    
                    'date_create' => new Expression('getdate()'),
                ])
                ->execute();
        }
    }

    /**
     * Get VoteMain by tag
     * @param string $tag
     * @return VoteMain
     */
    private function findModelMainByTag($tag)
    {
        if (($model = VoteMain::findOne(['tag' => $tag])) === null) {
            throw new NotFoundHttpException('Page not found');
        }
        return $model;
    }

    /**
     * Get VoteMain by id
     * @param string $tag
     * @return VoteMain
     */
    private function findModelMain($id)
    {
        if (($model = VoteMain::findOne($id)) === null) {
            throw new NotFoundHttpException('Page not found');
        }
        return $model;
    }




}
