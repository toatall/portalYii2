<?php

namespace app\modules\bookshelf\models;

use Yii;
use app\models\User;
use app\behaviors\DatetimeBehavior;
use app\behaviors\AuthorBehavior;

/**
 * This is the model class for table "{{%book_shelf_discussion_comment}}".
 *
 * @property int $id
 * @property int|null $id_parent
 * @property int $id_book_shelf_discussion
 * @property string $comment
 * @property string $username
 * @property string $date_create
 * @property string $date_update
 *
 * @property BookShelfDiscussion $bookShelfDiscussion
 * @property User $userModel
 */
class BookShelfDiscussionComment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book_shelf_discussion_comment}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [            
            [
                'class' => DatetimeBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
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
            [['id_parent', 'id_book_shelf_discussion'], 'integer'],
            [['id_book_shelf_discussion', 'comment'], 'required'],
            [['comment'], 'string'],
            [['date_create', 'date_update'], 'safe'],
            [['username'], 'string', 'max' => 250],
            [['id_book_shelf_discussion'], 'exist', 'skipOnError' => true, 'targetClass' => BookShelfDiscussion::class, 'targetAttribute' => ['id_book_shelf_discussion' => 'id']],
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
            'id_parent' => 'Id Parent',
            'id_book_shelf_discussion' => 'Id Book Shelf Discussion',
            'comment' => 'Коментарий',
            'username' => 'Username',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
        ];
    }

    /**
     * Gets query for [[BookShelfDiscussion]].
     * @return \yii\db\ActiveQuery
     */
    public function getBookShelfDiscussion()
    {
        return $this->hasOne(BookShelfDiscussion::class, ['id' => 'id_book_shelf_discussion']);
    }

    /**
     * Gets query for [[UserModel]].
     * @return \yii\db\ActiveQuery
     */
    public function getUserModel()
    {
        return $this->hasOne(User::class, ['username' => 'username']);
    }
    
    /**
     * Права редактора
     * @return boolean
     */
    public function isEditor()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        if ($this->username == Yii::$app->user->identity->username) {
            return true;
        }
        if (Yii::$app->user->can('admin')) {
            return true;
        }
        return false;
    }
}
