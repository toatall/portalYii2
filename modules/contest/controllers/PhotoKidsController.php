<?php

namespace app\modules\contest\controllers;

use app\modules\contest\models\photokids\DicEmployees;
use app\modules\contest\models\photokids\PhotoKids;
use app\modules\contest\models\quest\Crossword;
use app\modules\contest\models\quest\Linked;
use app\modules\contest\models\quest\MarkText;
use app\modules\contest\models\quest\Quest;
use app\modules\contest\models\quest\Questions;
use app\modules\contest\models\quest\Tasks;
use app\modules\contest\models\quest\TaxGroup;
use Yii;
use yii\db\Query;
use app\components\Controller;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;

/**
 * PhotoKids controller for the `contest` module
 */
class PhotoKidsController extends Controller
{

    public $layout = 'photo_kids';
    
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
     */
    public function actionIndex()
    {
        $listEmployee = DicEmployees::getList();
        
        if (Yii::$app->request->isPost) {
            $answer = Yii::$app->request->post('answer');
            $id = Yii::$app->request->post('id');
            PhotoKids::saveResult($id, $answer);
        }

        $tasksToday = PhotoKids::getToday();

        return $this->render('index', [     
            'listEmployee' => $listEmployee,
            'tasksToday' => $tasksToday,
            'results' => PhotoKids::getResults(),
        ]);
    }

    
    
}
