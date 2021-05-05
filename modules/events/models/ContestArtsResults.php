<?php

namespace app\modules\events\models;

use Yii;
use app\models\User;

/**
 * This is the model class for table "{{%contest_arts_results}}".
 *
 * @property int $id
 * @property int $id_arts
 * @property string $author
 * @property string $image_name
 * @property string $image_author
 * @property int|null $is_right
 * @property string|null $date_create
 *
 * @property ContestArts $arts
 * @property User $userModel
 */
class ContestArtsResults extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contest_arts_results}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_arts', 'author', 'image_name', 'image_author'], 'required'],
            [['id_arts', 'is_right'], 'integer'],
            [['date_create'], 'safe'],
            [['author'], 'string', 'max' => 250],
            [['image_name', 'image_author'], 'string', 'max' => 300],
            [['id_arts'], 'exist', 'skipOnError' => true, 'targetClass' => ContestArts::className(), 'targetAttribute' => ['id_arts' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_arts' => 'Id Arts',
            'author' => 'Author',
            'image_name' => 'Image Name',
            'image_author' => 'Image Author',
            'is_right' => 'Is Right',
            'date_create' => 'Date Create',
        ];
    }

    /**
     * Gets query for [[Arts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArts()
    {
        return $this->hasOne(ContestArts::className(), ['id' => 'id_arts']);
    }
    
    /**
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getUserModel()
    {
        return $this->hasOne(User::class, ['username' => 'author']);
    }
    
    /**
     * Список победителей
     * @return ContestArtsResults
     */
    public static function getWinners()
    {
        return self::find()
            ->select('')
            ->where(['is_right' => 1])
            ->andWhere('cast(date_create as date) < cast(getdate() as date)')
            ->all();
    }
}
