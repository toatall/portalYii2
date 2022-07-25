<?php

namespace app\modules\rookie\modules\fortboyard\controllers;

use app\modules\rookie\modules\fortboyard\models\FortBoyard;
use Yii;
use yii\base\DynamicModel;
use yii\db\Exception;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

/**
 * Default controller for the `fortboyard` module
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
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],            
        ];
    }


    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {      
        // загрузка текущего вопроса

        // загрузка результатов
        return $this->render('index', [
            'questionToday' => FortBoyard::todayQuestion(),
            'resultQuestions' => $this->resultQuestions(),
        ]);
    }

   
    /**
     * Статистика правильных ответов
     * @return array
     */
    protected function resultQuestions()
    {
        /*
        return Yii::$app->db->createCommand('
            SELECT t.id, t.name	
                ,(SELECT count(answ.id) 
                    FROM {{%fort_boyard_answers}} answ
                        LEFT JOIN {{%fort_boyard_access}} acc ON answ.username = acc.username
                        RIGHT JOIN {{%fort_boyard}} main ON main.id = answ.id_fort_boyard                       
                    WHERE answ.is_right = 1 AND acc.id_team = t.id AND main.date_show_2 < GETDATE()) count_rights
            FROM {{%fort_boyard_teams}} t	 
            ORDER BY t.name
        ')->queryAll();
        */
        return Yii::$app->db->createCommand("
            SELECT t.id, t.name	
                ,ISNULL((SELECT AVG(cast(vote.rating_trial as float)) 
                    FROM {{%fort_boyard_team_vote}} vote
                    WHERE vote.id_team = t.id 
                ), 0) avg_trial
                ,ISNULL((SELECT AVG(cast(vote.rating_name as float)) 
                    FROM {{%fort_boyard_team_vote}} vote
                    WHERE vote.id_team = t.id 
                ), 0) avg_name
            FROM {{%fort_boyard_teams}} t	 
            WHERE t.name <> '-'
            ORDER BY t.name
        ")->queryAll();
    }

    /**
     * Сохранение ответа
     * @param int $id
     * @return string
     */
    public function actionSaveAnswer($id)
    {
        $this->findModel($id);
        $answer = Yii::$app->request->post('answer');
        if ($answer != null) {
            if (!(new Query())->from('{{%fort_boyard_answers}}')->where([
                'id_fort_boyard' => $id,
                'username' => Yii::$app->user->identity->username,
            ])->exists()) {
                if (Yii::$app->db->createCommand()
                    ->insert('{{%fort_boyard_answers}}', [
                        'id_fort_boyard' => $id,
                        'answer' => $answer,
                        'username' => Yii::$app->user->identity->username,
                        'date_create' => new Expression('getdate()'),
                    ])->execute() != 0) {
                        Yii::$app->session->setFlash('success', 'Ваш ответ успешно сохранен!');
                    }
                    else {
                        Yii::$app->session->setFlash('danger', 'При сохранении произошла ошибка! Попробуйте еще раз!');
                    }
            }
        }
        return $this->redirect(['/rookie/fortboyard/default']);
    }    

    /**
     * Вывод голосовалки
     * @param int $idTeam
     * @return string
     */
    public function actionVote($idTeam)
    {
        if (!FortBoyard::canVoid($idTeam)) {
            throw new ServerErrorHttpException('Пользователь ' . Yii::$app->user->identity->username . ' не может голосовать!');
        }

        $modelVote = $this->findTeamModel($idTeam);

        $model = new DynamicModel(['rating_name', 'rating_trial']);
        $model->addRule(['rating_name', 'rating_trial'], 'safe');
        $model->setAttributeLabels([
            'rating_name' => 'За оригинальное название и девиз команды',
            'rating_trial' => 'За лучшее испытание',
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($modelVote['id_vote'] == null) {
                $this->saveVote($idTeam, $model['rating_trial'], $model['rating_name']);
            }
            else {
                throw new ServerErrorHttpException('Вы уже голосовали!');
            }
            return 'OK';
        }

        return $this->renderAjax('vote', [
            'model' => $model,
            'modelVote' => $modelVote,
        ]);
    }

    /**
     * Информация о результатах ответа команды
     * @param int $idTeam
     */
    public function actionInfo($idTeam)
    {
        return $this->renderAjax('info', [
            'model' => $this->myMission($idTeam),            
        ]);
    }

    /**
     * @param int $id
     * @return FortBoyard|null
     */
    private function findModel($id)
    {
        if (($model = FortBoyard::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Поиск команды по id
     * @param int $id
     * @return yii\db\Query
     */
    private function findTeamModel($id)
    {
        $query = (new Query())
            ->select('t.id, t.name, v.id as id_vote, v.date_create')
            ->from('{{%fort_boyard_teams}} t')
            ->leftJoin('{{%fort_boyard_team_vote}} v', 't.id = v.id_team and v.username=:username', [':username' => Yii::$app->user->identity->username])
            ->where([
                't.id' => $id,                
            ]);
        return $query->one();
    }

    /**
     * Сохранение результатов голосования
     * @param int $idTeam
     * @param int $voteTrial
     * @param int $voteName
     */
    private function saveVote($idTeam, $voteTrial, $voteName)
    {
        return Yii::$app->db->createCommand()
            ->insert('{{%fort_boyard_team_vote}}', [
                'id_team' => $idTeam,
                'username' => Yii::$app->user->identity->username,
                'rating_trial' => is_numeric($voteTrial) ? $voteTrial : 0,
                'rating_name' => is_numeric($voteName) ? $voteName : 0,
                'date_create' => new Expression('getdate()'),
            ])
            ->execute();
    }

    /**
     * Результаты выполненных заданий команды с id = $idTeam
     * @param int $idTeam
     * @return yii\db\Query
     */
    private function allMissions($idTeam)
    {        
        return Yii::$app->db->createCommand("
            SELECT DISTINCT [t].*, [team].[name] AS [team_name]
                ,(SELECT TOP 1 tt.[is_right] FROM {{%fort_boyard_answers}} tt
                    LEFT JOIN {{%fort_boyard_access}} acc ON acc.username = tt.username
                    LEFT JOIN {{%fort_boyard_teams}} team_answ ON team_answ.id = acc.id_team
                WHERE tt.id_fort_boyard = t.id AND team_answ.id = :id_team) as is_right
            FROM {{%fort_boyard}} [t] 
            LEFT JOIN {{%fort_boyard_teams}} [team] ON team.id=t.id_team 
            WHERE NOT ([team].name='-') AND NOT ([team].id = :id_team_2)
            ORDER BY [date_show_1]
        ", [
            ':id_team' => $idTeam,
            ':id_team_2' => $idTeam,
        ])
        ->queryAll();        
    }

    private function myMission($idTeam)
    {
        return Yii::$app->db->createCommand("
            SELECT DISTINCT [t].*, [team].[name] AS [team_name]
                ,(SELECT TOP 1 tt.[is_right] FROM {{%fort_boyard_answers}} tt
                    LEFT JOIN {{%fort_boyard_access}} acc ON acc.username = tt.username
                    LEFT JOIN {{%fort_boyard_teams}} team_answ ON team_answ.id = acc.id_team
                WHERE tt.id_fort_boyard = t.id AND team_answ.id = :id_team) as is_right
            FROM {{%fort_boyard}} [t] 
            LEFT JOIN {{%fort_boyard_teams}} [team] ON team.id=t.id_team 
            WHERE NOT ([team].name='-') AND ([team].id = :id_team_2)
            ORDER BY [date_show_1]
        ", [
            ':id_team' => $idTeam,
            ':id_team_2' => $idTeam,
        ])
        ->queryAll();    
    }
    
}
