<?php

namespace app\models\lifehack;

use app\behaviors\AuthorBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%lifehack_like}}".
 *
 * @property int $id
 * @property int $id_lifehack
 * @property int $rate
 * @property string $username 
 * @property int $date_create
 *
 * @property Lifehack $lifehack
 */
class LifehackLike extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lifehack_like}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_lifehack'], 'required'],
            [['id_lifehack', 'date_create', 'rate'], 'integer'],
            [['username'], 'string', 'max' => 250],
            [['id_lifehack'], 'exist', 'skipOnError' => true, 'targetClass' => Lifehack::class, 'targetAttribute' => ['id_lifehack' => 'id']],
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
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        if ($this->rate == null) {
            $this->delete();
            return false;
        }
        return true;
    }

}
