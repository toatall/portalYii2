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
            ->orderBy([
                'date_received' => SORT_DESC,
                'date_create' => SORT_DESC,
            ])
            ->limit($limit)
            ->where(['book_status' => BookShelf::STATUS_IN_STOCK])
            ->all();
    }

    /**
     * Кто родился или умер в этот день
     * @param int $limit
     * @return BookShelfCalendar[]|null
     */
    private function calendarToday($limit=3)
    {
        return BookShelfCalendar::find()
            // выбирать писателей у которых +-3 дня от даты рождения или от даты смерти
            ->where("
                (
                    CAST(
                        CAST(DATEPART(DAY, date_birthday) AS NVARCHAR) + '.' +
                        CAST(DATEPART(MONTH, date_birthday) AS NVARCHAR) + '.' +
                        CAST(DATEPART(YEAR, GETDATE()) AS NVARCHAR)
                        AS DATE
                    ) BETWEEN CAST(DATEADD(DAY,-7,GETDATE()) AS DATE)
                        AND CAST(DATEADD(DAY,7,GETDATE()) AS DATE)
                ) OR
                (
                    CAST(
                        CAST(DATEPART(DAY, date_die) AS NVARCHAR) + '.' +
                        CAST(DATEPART(MONTH, date_die) AS NVARCHAR) + '.' +
                        CAST(DATEPART(YEAR, GETDATE()) AS NVARCHAR)
                        AS DATE
                    ) BETWEEN CAST(DATEADD(DAY,-7,GETDATE()) AS DATE)
                        AND CAST(DATEADD(DAY,7,GETDATE()) AS DATE)
                )
            ")
            ->limit($limit)
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
