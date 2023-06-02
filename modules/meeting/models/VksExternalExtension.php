<?php

namespace app\modules\meeting\models;

use Yii;

/**
 * This is the model class for table "{{%meeting_vks_external}}".
 *
 * @property int $id
 * @property int $id_meeting
 * @property string $format_holding
 * @property string $person_head
 * @property int $members_count
 * @property int $members_count_ufns
 * @property string $material_translation
 * @property string|null $link_event
 * @property int|null $is_connect_vks_fns
 * @property string|null $platform
 * @property string|null $full_name_support_ufns
 * @property int|null $date_test_vks
 * @property int|null $count_notebooks
 *
 * @property Meeting $meeting
 */
class VksExternalExtension extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%meeting_vks_external}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['format_holding', 'person_head', 'members_count', 'members_count_ufns', 'material_translation'], 'required'],
            [['id_meeting', 'members_count', 'members_count_ufns', 'is_connect_vks_fns', 
                'count_notebooks', 'date_test_vks'], 'integer'],
            [['format_holding'], 'string', 'max' => 50],           
            [['date_test_vks_str'], 'match', 'pattern' => '/^\d{2}\.\d{2}\.\d{4} \d{2}:\d{2}/'],
            [['person_head', 'link_event', 'full_name_support_ufns'], 'string', 'max' => 500],
            [['material_translation', 'platform'], 'string', 'max' => 250],
            [['id_meeting'], 'exist', 'skipOnError' => true, 'targetClass' => Meeting::class, 'targetAttribute' => ['id_meeting' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_meeting' => 'Id Meeting',
            'date_test_vks_str' => 'Дата проведения тестового ВКС',
            'format_holding' => 'Формат проведения',
            'person_head' => 'Председательствующий (руководитель, заместитель)',
            'members_count' => 'Количество участников',
            'members_count_ufns' => 'Количество участников со стороны Управления',
            'material_translation' => 'Материалы для трансляции',
            'link_event' => 'Ссылка на мероприятие',
            'is_connect_vks_fns' => 'Подключение к ВКС ЦА ФНС России',
            'platform' => 'Платформа',
            'full_name_support_ufns' => 'ФИО тех. специалиста Управления',
            'date_test_vks' => 'Дата проведения тестового ВКС',
            'count_notebooks' => 'Количество ноутбуков',            
        ];
    }

    /**
     * @inheritDoc
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        //$this->date_test_vks = \app\helpers\DateHelper::dateTimeToUnix($this->date_test_vks);
        return true;
    }

    /**
     * Gets query for [[Meeting]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeeting()
    {
        return $this->hasOne(Meeting::class, ['id' => 'id_meeting']);
    }

    /**
     * Список для поля `material_translation`
     * 
     * @return array
     */
    public static function dropDownListMaterialTranslation()
    {
        return [
            'нет' => 'нет',
            'видео' => 'видео',
            'презентация' => 'презентация',
        ];
    }      

    /**
     * Список для поля `format_holding`
     * 
     * @return array
     */
    public static function dropDownListFormatHolding()
    {
        return [
            'вебинар' => 'вебинар',
            'видеоконференция' => 'видеоконференция',
        ];
    }

    /**
     * @param string $dateTime
     */
    public function setDate_test_vks_str($dateTime)
    {
        $this->date_test_vks = strtotime($dateTime);        
    }

    /**
     * @return string|null
     */
    public function getDate_test_vks_str()
    {
        if (empty($this->date_test_vks)) {
            return null;
        }
        return Yii::$app->formatter->asDatetime($this->date_test_vks, 'php:d.m.Y H:i');
    }
    

}
