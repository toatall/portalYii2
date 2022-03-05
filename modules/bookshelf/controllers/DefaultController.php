<?php

namespace app\modules\bookshelf\controllers;

use app\modules\bookshelf\models\BookShelf;
use app\modules\bookshelf\models\BookShelfCalendar;
use app\modules\bookshelf\models\BookShelfDiscussion;
use app\modules\bookshelf\models\WhatReading;
use yii\db\Expression;
use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * DefaultController for the `bookshelf` module
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
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['error'],
                        'roles' => ['?', '@'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    
    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'modelLastBooks' => $this->lastBooks(),
            'modelCalendarToday' => $this->calendarToday(),
            'modelLastWhatReading' => $this->lastWhatReading(),
            'modelLastDiscussion' => $this->lastDiscussion(),
        ]);
    }

    /**
     * Последние добавленные книги
     * @param int $limit
     * @return BookShelf[]|null
     */
    private function lastBooks($limit=5)
    {
        return BookShelf::find()
            ->orderBy(['id' => SORT_DESC])
            ->limit($limit)
            ->where(['book_status' => BookShelf::STATUS_IN_STOCK])
            ->all();
    }

    /**
     * Кто родился или умер в этот день
     * @return BookShelfCalendar[]|null
     */
    private function calendarToday()
    {
        return BookShelfCalendar::find()
            ->where(['or', 
                ['date_birthday' => new Expression('cast(getdate() as date)')],
                ['date_die' => new Expression('cast(getdate() as date)')],
            ])
            ->all();
    }

    /**
     * Несколько последних записей из рубрики "Кто что читает"
     * @param int $limit
     * @return WhatReading[]|null
     */
    private function lastWhatReading($limit=3)
    {
        return WhatReading::find()
            ->orderBy(['id' => SORT_DESC])
            ->limit($limit)
            ->all();
    }

    /**
     * Последние записи по дискуссии
     * @param int $limit
     * @return BookShelfDiscussion[]|null
     */
    private function lastDiscussion($limit=3)
    {
        return BookShelfDiscussion::find()
            ->orderBy(['id' => SORT_DESC])
            ->limit($limit)
            ->all();
    }


}
