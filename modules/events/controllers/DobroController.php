<?php

namespace app\modules\events\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use app\modules\events\models\Dobro;


/**
 * ContestArtsController implements the CRUD actions for ContestArts model.
 */
class DobroController extends Controller
{
  
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],                    
                ],                
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'dobro';
                
        return $this->render('index', [
            'models' => $this->findAll(),
        ]);
    }
    
    
    /**
     * @return array
     */
    protected function findAll()
    {
        $model = new Dobro();
        return $model->getData();
    }
    
    
        
    
}
