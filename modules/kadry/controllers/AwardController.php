<?php

namespace app\modules\kadry\controllers;

use app\modules\kadry\models\Award;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class AwardController extends Controller
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
                        'roles' => ['@',],
                    ],                
                ],
            ],           
        ];
    }
    

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        /** @var yii\db\Connection $db */       
        // $db = Yii::$app->dbDKS;
        // $query = (new Query())
        //     ->from('awards');
        $searchModel = new Award();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            // 'dataProvider' => new ActiveDataProvider([
            //     'query' => $query,
            //     'db' => $db,
            // ])
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    


}
