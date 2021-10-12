<?php

namespace app\modules\contest\models;

use Yii;

/**
 * This is the model class for table "{{%contest_hr_people}}".
 *
 * @property int $id
 * @property string $fio
 * @property string $photo
 * @property string $date_create
 *
 * @property ContestHrResultData[] $contestHrResultDatas
 */
class HrPeople extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contest_hr_people}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fio', 'photo', 'date_create'], 'required'],
            [['date_create'], 'safe'],
            [['fio'], 'string', 'max' => 500],
            [['photo'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fio' => 'Fio',
            'photo' => 'Photo',
            'date_create' => 'Date Create',
        ];
    }

    /**
     * Gets query for [[ContestHrResultDatas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContestHrResultDatas()
    {
        return $this->hasMany(HrResultData::class, ['id_hr_people' => 'id']);
    }

    /**
     * Генерирование температуры
     */
    public function getTemp()
    {
        return (rand(355, 368) / 10);
    }


}
