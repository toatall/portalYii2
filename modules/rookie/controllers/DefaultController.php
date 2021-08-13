<?php

namespace app\modules\rookie\controllers;

use yii\web\Controller;

/**
 * Default controller for the `roockie` module
 */
class DefaultController extends Controller
{

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
        return $this->render('index');
    }
}
