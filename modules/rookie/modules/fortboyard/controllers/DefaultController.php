<?php

namespace app\modules\rookie\modules\fortboyard\controllers;

use app\modules\rookie\modules\fortboyard\models\FortBoyard;
use app\modules\rookie\modules\photohunter\models\Photos;
use app\modules\rookie\modules\photohunter\models\PhotosVotes;
use Yii;
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

    /**
     * Render vote form
     * @return string
     */
    public function actionVote(int $id)
    {
        $modelPhotos = $this->findModel($id);
        
        if (!$modelPhotos->canVote()) {
            throw new ServerErrorHttpException('Вам запрещено голосовать!');
        }

        $model = $this->findModelVote($id);
        if ($model === null) {
            $model = new PhotosVotes();
            $model->id_photos = $id;            
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isAjax) {
                return 'OK';
            }
            else {
                return $this->redirect(['/rookie/photohunter']);
            }
        }

        if (Yii::$app->request->isAjax) {
           return $this->renderAjax('vote', [
                'model'=>$model,
                'modelPhotos'=>$modelPhotos,
            ]);
        }       

        return $this->render('vote', [
            'model'=>$model,
            'modelPhotos'=>$modelPhotos,
        ]);
    }

    /**
     * @param int $id
     * @return Photos|null
     */
    private function findModel($id)
    {
        if (($model = Photos::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Find PhotoVotes model binding to id_photos and current user
     * @param int $idPhotos
     * @return PhotosVotes|null
     */
    private function findModelVote(int $idPhotos)
    {
        return PhotosVotes::find()->where([
            'id_photos' => $idPhotos,
            'username' => Yii::$app->user->identity->username,
        ])
        ->one();
    }

    
}
