<?php

namespace app\modules\contest\models;

use Yii;

/**
 * This is the model class for table "{{%contest_hr_result_data}}".
 *
 * @property int $id
 * @property int $id_hr_result
 * @property int $id_hr_people
 * @property string $temperature
 * @property string|null $temperature_user
 *
 * @property HrPeople $hrPeople
 * @property HrResult $hrResult
 */
class HrResultData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contest_hr_result_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_hr_result', 'id_hr_people', 'temperature'], 'required'],
            [['id_hr_result', 'id_hr_people'], 'integer'],
            [['temperature', 'temperature_user'], 'number'],
            [['id_hr_people'], 'exist', 'skipOnError' => true, 'targetClass' => HrPeople::class, 'targetAttribute' => ['id_hr_people' => 'id']],
            [['id_hr_result'], 'exist', 'skipOnError' => true, 'targetClass' => HrResult::class, 'targetAttribute' => ['id_hr_result' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_hr_result' => 'Id Hr Result',
            'id_hr_people' => 'Id Hr People',
            'temperature' => 'Temperature',
            'temperature_user' => 'Temperature User',
        ];
    }

    /**
     * Gets query for [[HrPeople]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHrPeople()
    {
        return $this->hasOne(HrPeople::class, ['id' => 'id_hr_people']);
    }

    /**
     * Gets query for [[HrResult]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHrResult()
    {
        return $this->hasOne(HrResult::class, ['id' => 'id_hr_result']);
    }
}
