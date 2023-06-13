<?php

namespace app\modules\like\models;

use app\behaviors\AuthorBehavior;
use app\models\User;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%like_data}}".
 *
 * @property int $id
 * @property int|null $id_like
 * @property string $username
 * @property int $date_create
 *
 * @property Like $like
 * @property User $usernameModel
 */
class LikeData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%like_data}}';
    }

    /**
     * {@inheritDoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => AuthorBehavior::class,
                'author_at' => 'username',
            ],
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => null,
            ],
        ];
    }  
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsernameModel()
    {
        return $this->hasOne(User::class, ['username' => 'username']);
    }

}
