<?php

namespace app\modules\quiz\controllers;

use app\modules\quiz\models\Quiz;
use app\modules\quiz\models\QuizResult;
use app\modules\quiz\models\QuizResultQuestion;
use Intervention\Image\Exception\NotFoundException;
use Yii;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\ServerErrorHttpException;

/**
 * Default controller for the `quiz` module
 */
class DefaultController extends Controller
{
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
    // public function actionIndex()
    // {
    //     return $this->render('index');
    // }

    public function actionView($id)
    {
        $model = $this->findQuiz($id);

        $postData = Yii::$app->request->post('Quiz');
        if ($postData !== null) {
            $this->saveQuizResult($id, $postData);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    private function findQuiz($id)
    {
        if (($model = Quiz::findOne($id)) === null) {
            throw new NotFoundException('Page not found');
        }
        return $model;
    }

    private function saveQuizResult($id, $postData)
    {
        $modelResult = new QuizResult([
           'id_quiz' => $id,           
        ]);
        if (!$modelResult->save()) {
            throw new ServerErrorHttpException("Не удалось сохранить результат опроса {$id}");
        }
        
        foreach($postData as $dataId => $dataValue) {
            (new QuizResultQuestion([
                'id_result' => $modelResult->id,
                'id_question' => $dataId,
                'value' => $dataValue,
            ]))->save();
        }
    
    }

}
