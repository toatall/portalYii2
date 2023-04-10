<?php
namespace app\modules\project\controllers;

use app\components\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}