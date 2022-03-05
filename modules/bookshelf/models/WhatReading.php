<?php

namespace app\modules\bookshelf\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\ChangeLogBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%book_shelf_what_reading}}".
 *
 * @property int $id
 * @property string $fio
 * @property string $writer
 * @property string $title
 * @property string $author
 * @property string $date_create
 * @property string $date_update
 * @property string $log_change
 *
 * @property User $authorModel
 */
class WhatReading extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book_shelf_what_reading}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [            
            [
                'class' => DatetimeBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
            ],
            ['class' => AuthorBehavior::class],  
            ['class' => ChangeLogBehavior::class],         
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fio', 'writer', 'title'], 'required'],
            [['date_create', 'date_update'], 'safe'],
            [['log_change'], 'string'],
            [['fio', 'author'], 'string', 'max' => 250],
            [['writer'], 'string', 'max' => 500],
            [['title'], 'string', 'max' => 1000],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'fio' => 'ФИО',
            'writer' => 'Писатель',
            'title' => 'Название',
            'author' => 'Автор',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'log_change' => 'Журнал изменений',
        ];
    }

    /**
     * Gets query for [[AuthorModel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorModel()
    {
        return $this->hasOne(User::class, ['username' => 'author']);
    }
}
