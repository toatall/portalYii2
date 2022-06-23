<?php

namespace app\modules\bookshelf\controllers;

use app\modules\bookshelf\models\BookShelf;
use app\modules\bookshelf\models\BookShelfCalendar;
use app\modules\bookshelf\models\BookShelfDiscussion;
use app\modules\bookshelf\models\RecommendRead;
use app\modules\bookshelf\models\WhatReading;
use yii\data\ActiveDataProvider;
use yii\db\Query;
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
        $dataProviderBooks = new ActiveDataProvider([
            'query' => $this->lastBooks(),
            'pagination' => [
                'pageSize' => 1,
            ],
        ]);

        return $this->render('index', [
            'dataProviderBooks' => $dataProviderBooks,
            'modelCalendarToday' => $this->calendarToday(),
            'modelLastWhatReading' => $this->lastWhatReading(),
            'modelLastDiscussion' => $this->lastDiscussion(),
            'discussions' => $this->discussions(),
            'recommend' => $this->recommend(),
        ]);
    }

    /**
     * Последние добавленные книги
     * @return \yii\db\Query
     */
    private function lastBooks()
    {
        return BookShelf::find()
            ->orderBy([
                'date_received' => SORT_DESC,
                'date_create' => SORT_DESC,
            ])                        
            ->where(['book_status' => BookShelf::STATUS_IN_STOCK]);
    }

    /**
     * Кто родился в этот месяц
     * @param int $limit
     * @return BookShelfCalendar[]|null
     */
    private function calendarToday()
    {
        return BookShelfCalendar::find()        
            ->where('month(date_birthday) = month(getdate())')
            ->orderBy('day(date_birthday) asc')
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

    /**
     * Количество дискуссий
     * @return array
     */
    private function discussions()
    {
        $query = (new Query())
            ->from('{{%comment}}')
            ->where(['model_name' => 'bookshelf'])
            ->select('model_id, count(id) count')
            ->groupBy('model_id')
            ->all();
        return $query;
    }

    /**
     * Рекомендации за последние 7 дней
     * @return RecommendRead[]|null
     */
    public function recommend()
    {
        return RecommendRead::findPublic()->all();
    }


}
