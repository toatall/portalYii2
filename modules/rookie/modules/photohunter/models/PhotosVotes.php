<?php

namespace app\modules\rookie\modules\photohunter\models;

use Yii;

/**
 * This is the model class for table "{{%rookie_photohunter_photos_votes}}".
 *
 * @property int $id
 * @property int $id_photos
 * @property int $mark_creative
 * @property int $mark_art
 * @property int $mark_original
 * @property int $mark_accordance
 * @property int $mark_quality
 * @property string $username
 * @property string|null $date_create
 *
 * @property RookiePhotohunterPhotos $photos
 */
class PhotosVotes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%rookie_photohunter_photos_votes}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_photos', 'mark_creative', 'mark_art', 'mark_original', 'mark_accordance', 'mark_quality', 'username'], 'required'],
            [['id_photos', 'mark_creative', 'mark_art', 'mark_original', 'mark_accordance', 'mark_quality'], 'integer'],
            [['date_create'], 'safe'],
            [['username'], 'string', 'max' => 250],
            [['id_photos'], 'exist', 'skipOnError' => true, 'targetClass' => Photos::class, 'targetAttribute' => ['id_photos' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_photos' => 'Id Photos',
            'mark_creative' => 'Креативность',
            'mark_art' => 'Творческий подход',
            'mark_original' => 'Оригинальность идеи',
            'mark_accordance' => 'Степень соответствия снимка заданию',
            'mark_quality' => 'Качество снимка',
            'username' => 'Username',
            'date_create' => 'Date Create',
        ];
    }

    /**
     * Gets query for [[Photos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPhotos()
    {
        return $this->hasOne(Photos::class, ['id' => 'id_photos']);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeValidate()
    {        
        $this->username = Yii::$app->user->identity->username;
        return parent::beforeValidate();
    }

}
