<?php

namespace app\modules\rookie\modules\fortboyard\controllers;

use app\modules\rookie\modules\fortboyard\models\FortBoyard;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
        return Yii::$app->db->createCommand('
            SELECT t.name	
                ,(SELECT count(answ.id) 
                    FROM {{%fort_boyard_answers}} answ
                        LEFT JOIN {{%fort_boyard_access}} acc ON answ.username = acc.username
                        RIGHT JOIN {{%fort_boyard}} main ON main.id = answ.id_fort_boyard                       
                    WHERE answ.is_right = 1 AND acc.id_team = t.id AND main.date_show_2 > GETDATE()) count_rights
            FROM {{%fort_boyard_teams}} t	 
            ORDER BY t.name
        ')->queryAll();
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

    
}
