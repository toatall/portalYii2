<?php

namespace app\modules\bookshelf\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "{{%book_shelf_recomend_read}}".
 *
 * @property int $id
 * @property string $fio
 * @property string|null $writer
 * @property string $book_name
 * @property string|null $description
 * @property string $date_create
 * @property string $date_update
 * @property string $author
 */
class RecommendRead extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book_shelf_recommend_read}}';
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fio', 'book_name'], 'required'],
            [['description'], 'string'],
            [['date_create', 'date_update'], 'safe'],
            [['fio', 'writer', 'book_name'], 'string', 'max' => 500],
            [['author'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'fio' => 'ФИО сотрудника',
            'writer' => 'Писатель',
            'book_name' => 'Наименование книги',
            'description' => 'Описание',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'author' => 'Автор',
        ];
    }

    /**
     * @return yii\db\ActiveQueryInterface
     */
    public static function findPublic()
    {
        return self::find()
            ->where(['>=', new Expression('dateadd(day, 7, date_create)'), new Expression('getdate()')]);
    }

}
