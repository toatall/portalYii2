<?php

namespace app\modules\spa\controllers;

use Yii;
use yii\filters\AccessControl;
use app\components\Controller;

/**
 * Education controller for the `kadry` module
 */
class BookVnpController extends Controller
{
    /**
     * {@inheritdoc}
     * without layout
     */
    public $layout = false;

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
        Yii::setAlias('@book-vnp-asset', '@web/public/assets/spa/book-vnp');
        return $this->render('index');
    }

}