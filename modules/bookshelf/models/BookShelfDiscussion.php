<?php

namespace app\modules\bookshelf\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\ChangeLogBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%book_shelf_discussion}}".
 *
 * @property int $id
 * @property string $title
 * @property string|null $note
 * @property string $author
 * @property string $date_create
 * @property string $date_update
 * @property string $log_change
 *
 * @property User $authorModel
 * @property BookShelfDiscussionComment[] $bookShelfDiscussionComments
 */
class BookShelfDiscussion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book_shelf_discussion}}';
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
            [['title', 'note'], 'required'],
            [['note', 'log_change'], 'string'],
            [['date_create', 'date_update'], 'safe'],
            [['title'], 'string', 'max' => 1000],
            [['author'], 'string', 'max' => 250],
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
            'title' => 'Заголовок',
            'note' => 'Описание',
            'author' => 'Автор',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'log_change' => 'Журнал изменений',
        ];
    }
    
    public function beforeDelete() 
    {
        if (!parent::beforeDelete()) {
            return false;
        }
        foreach ($this->bookShelfDiscussionComments as $model) {
            $model->delete();
        }
        return true;
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

    /**
     * Gets query for [[BookShelfDiscussionComments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookShelfDiscussionComments()
    {
        return $this->hasMany(BookShelfDiscussionComment::class, ['id_book_shelf_discussion' => 'id']);
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
        if ($this->author == Yii::$app->user->identity->username) {
            return true;
        }
        if (Yii::$app->user->can('admin')) {
            return true;
        }
        return false;
    }
}
