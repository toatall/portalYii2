<?php

namespace app\modules\rookie\modules\fortboyard\controllers;

use app\modules\rookie\modules\fortboyard\models\FortBoyard;
use app\modules\rookie\modules\photohunter\models\Photos;
use app\modules\rookie\modules\photohunter\models\PhotosVotes;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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

   

    protected function resultQuestions()
    {

    }

    

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
