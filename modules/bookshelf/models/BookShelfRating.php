<?php

namespace app\modules\bookshelf\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%book_shelf_rating}}".
 *
 * @property int $id
 * @property int $id_book_shelf
 * @property float|null $rating
 * @property string $username
 * @property string|null $date_create
 *
 * @property BookShelf $bookShelf
 * @property User $username0
 */
class BookShelfRating extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book_shelf_rating}}';
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
                'updatedAtAttribute' => null,
            ],
            [
                'class' => AuthorBehavior::class,
                'author_at' => 'username',
            ],              
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_book_shelf'], 'required'],
            [['id_book_shelf'], 'integer'],
            [['rating'], 'number'],
            [['date_create'], 'safe'],
            [['username'], 'string', 'max' => 250],
            [['id_book_shelf'], 'exist', 'skipOnError' => true, 'targetClass' => BookShelf::class, 'targetAttribute' => ['id_book_shelf' => 'id']],
            [['username'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['username' => 'username']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_book_shelf' => 'Id Book Shelf',
            'rating' => 'Rating',
            'username' => 'Username',
            'date_create' => 'Date Create',
        ];
    }

    /**
     * Gets query for [[BookShelf]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookShelf()
    {
        return $this->hasOne(BookShelf::class, ['id' => 'id_book_shelf']);
    }

    /**
     * Gets query for [[Username0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsername0()
    {
        return $this->hasOne(User::class, ['username' => 'username']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->bookShelf->updateRating();
    }
}
