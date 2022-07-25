<?php

namespace app\modules\contest\controllers;

use app\modules\contest\models\Crossword;
use app\modules\contest\models\quest\Linked;
use Yii;
use app\components\Controller;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use app\modules\contest\models\ManualNeighbor;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * ManualNeighbor controller for the `contest` module
 */
class ManualNeighborController extends Controller
{

    public $layout = '/portal';
   
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
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ManualNeighbor::find()->orderBy(new Expression('count_votes_1 + count_votes_2 + count_votes_3 desc, id asc')),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Голосование 
     * @return string
     */
    public function actionVote()
    {        
        $model = new DynamicModel(['vote1', 'vote2', 'vote3']);     
        $model->addRule(['vote1', 'vote2', 'vote3'], 'required');
        $model->setAttributeLabels([
            'vote1' => 'Разберётся и ребенок',
            'vote2' => 'Охват аудитории',
            'vote3' => 'Глаза разбегаются',
        ]);

        //$saved = false;
  
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (ManualNeighbor::getMyVote() == null) {
                if (ManualNeighbor::saveVote($model) != 0) {
                    return $this->redirect(['/contest/manual-neighbor']);
                }
            }
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => 'Голосование',
            'content' => $this->renderAjax('vote', [
                'model' => $model,            
            ]),
        ];
    }
    
    
}
