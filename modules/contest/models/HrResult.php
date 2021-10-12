<?php

namespace app\modules\contest\models;

use Yii;

/**
 * This is the model class for table "{{%contest_hr_result}}".
 *
 * @property int $id
 * @property string $username
 * @property string $date_create
 *
 * @property ContestHrResultData[] $contestHrResultDatas
 */
class HrResult extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contest_hr_result}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'date_create'], 'required'],
            [['date_create'], 'safe'],
            [['username'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
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
        return $this->hasMany(HrResultData::class, ['id_hr_result' => 'id']);
    }
}
