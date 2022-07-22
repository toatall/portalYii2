<?php

namespace app\modules\rookie\modules\tiktok\models;

use app\behaviors\AuthorBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "{{%tiktok_vote}}".
 *
 * @property int $id
 * @property int $id_tiktok
 * @property int $rate_1
 * @property int $rate_2
 * @property int $rate_3
 * @property int $date_create
 * @property string $author
 *
 * @property Tiktok $tiktok
 */
class TiktokVote extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tiktok_vote}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tiktok', 'rate_1', 'rate_2', 'rate_3'], 'required'],
            [['id_tiktok', 'rate_1', 'rate_2', 'rate_3', 'date_create'], 'integer'],
            [['author'], 'string', 'max' => 250],
            [['id_tiktok'], 'exist', 'skipOnError' => true, 'targetClass' => Tiktok::class, 'targetAttribute' => ['id_tiktok' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_tiktok' => 'Id Tiktok',
            'rate_1' => 'Креативность',
            'rate_2' => 'Творчество',
            'rate_3' => 'Качество видеоролика',
            'date_create' => 'Date Create',
            'author' => 'Author',
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
            ['class' => AuthorBehavior::class],        
        ];
    }
    

    /**
     * Gets query for [[Tiktok]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTiktok()
    {
        return $this->hasOne(Tiktok::class, ['id' => 'id_tiktok']);
    }


}
