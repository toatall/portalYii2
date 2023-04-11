<?php

namespace app\modules\contest\modules\space\models;

use app\behaviors\AuthorBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%contest_space_like}}".
 *
 * @property int $id
 * @property int $id_space
 * @property string $author
 * @property int|null $date_create
 *
 * @property User $authorModel
 * @property Space $space
 */
class SpaceLike extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contest_space_like}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_space', 'author'], 'required'],
            [['id_space', 'date_create'], 'integer'],
            [['author'], 'string', 'max' => 250],
            [['id_space'], 'exist', 'skipOnError' => true, 'targetClass' => Space::class, 'targetAttribute' => ['id_space' => 'id']],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [            
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => false,
            ],
            [
                'class' => AuthorBehavior::class,
                'author_at' => 'author',
            ],            
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

    /**
     * Gets query for [[Space]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSpace()
    {
        return $this->hasOne(ContestSpace::class, ['id' => 'id_space']);
    }
}
