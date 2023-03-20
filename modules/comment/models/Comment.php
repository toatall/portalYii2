<?php

namespace app\modules\comment\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\ChangeLogBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\User;

/**
 * This is the model class for table "p_comment".
 *
 * @property int $id
 * @property int $id_parent
 * @property int $id_reply
 * @property string $model_name
 * @property int $model_id
 * @property string $bind_hash
 * @property string $url
 * @property string $username
 * @property string|null $text
 * @property string $date_create
 * @property string $date_update
 * @property string|null $date_delete
 * @property string|null $log_change
 *
 * @property User $usernameModel
 * @property Comment $reply
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%comment}}';
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
            [
                'class' => AuthorBehavior::class,
                'author_at' => 'username',
            ],  
            ['class' => ChangeLogBehavior::class],         
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bind_hash', 'url', 'text'], 'required'],
            [['id_parent', 'id_reply'], 'integer'],
            [['text', 'log_change'], 'string'],
            [['date_create', 'date_update', 'date_delete'], 'safe'],
            [['bind_hash'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 1000],
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
            'id' => 'ID',
            'id_parent' => 'Id Parent',
            'bind_hash' => 'Bind Hash',
            'url' => 'Url',
            'username' => 'Username',
            'text' => 'Комментарий',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'date_delete' => 'Date Delete',
            'log_change' => 'Log Change',
        ];
    }

    /**
     * Gets query for [[Username0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsernameModel()
    {
        return $this->hasOne(User::class, ['username' => 'username']);
    }

    /**
     * @return Comment|null комментарий, на который был выполнен ответ
     */
    public function getReply()
    {
        return $this->hasOne(Comment::class, ['id' => 'id_reply']);
    }

    /**
     * Автор сообщения
     * @return boolean
     */
    public function isAuthor()
    {
        if (\Yii::$app->user->isGuest) {
            return false;
        }
        return $this->username === \Yii::$app->user->identity->username;
    }

    /**
     * @return array
     */
    public static function getComments($hash)
    {
        return self::recursive($hash, null);
    }

    /**
     * @return array
     */
    private static function recursive($hash, $idParent)
    {
        $query = Comment::find()->where([
                'bind_hash' => $hash,
                'id_parent' => $idParent,
            ])
            ->all();

        if ($query == null) {
            return null;
        }

        $result = [];
        foreach ($query as $item) {
            $result[] = [
                'modelComment' => $item,
                'subComment' => self::recursive($hash, $item->id)
            ];
        }
        return $result;
    }
    
}
