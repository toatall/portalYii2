<?php

namespace app\modules\bookshelf\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%book_shelf_place}}".
 *
 * @property int $id
 * @property string|null $place
 * @property string $username
 * @property string $date_create
 *
 * @property User $usernameModel
 */
class BookShelfPlace extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book_shelf_place}}';
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
            [['place'], 'required'],
            [['date_create'], 'safe'],
            [['place'], 'string', 'max' => 100],
            [['username'], 'string', 'max' => 250],        
            [['username'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['username' => 'username']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',          
            'place' => 'Место',
            'username' => 'Автор',
            'date_create' => 'Дата создания',
        ];
    }
   

    /**
     * Gets query for [[UsernameModel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsernameModel()
    {
        return $this->hasOne(User::class, ['username' => 'username']);
    }
}
