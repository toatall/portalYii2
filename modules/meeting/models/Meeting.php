<?php
namespace app\modules\meeting\models;

use app\modules\meeting\helpers\MeetingHelper;
use app\modules\meeting\models\ar\ARMeeting;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%meeting}}".
 * 
 */
abstract class Meeting extends ARMeeting
{

    /**
     * Тип события (например, `conference`, `vks`, ...)
     * 
     * @return string
     */
    abstract public static function getType(): string;

    /**
     * Описание типа события (например, `собрание`, `видеоконференция`, ....)
     *
     * @return string
     */
    abstract public static function getTypeLabel(): string;

    /**
     * Роль редактора
     * @return string
     */
    abstract public static function roleEditor(): string;

    /**
     * Роль просмотра всех полей
     * @return string
     */
    abstract public static function roleViewerAllFields(): string|bool;

    /**
     * Проверка прав редактора
     * @return bool
     */
    public static function isEditor(): bool
    {        
        if (Yii::$app->user->can('admin')) {
            return true;
        }
        return Yii::$app->grantAccess->can(static::roleEditor());
    }

    /**
     * Проверка прав для просмотра всех полей
     * @return bool
     */
    public static function isViewerAllFields()
    {
        if (Yii::$app->user->can('admin')) {
            return true;
        }
        $role = static::roleViewerAllFields();
        if ($role === true) {
            return true;
        }
        if ($role === false) {
            return false;
        }        
        return Yii::$app->grantAccess->can($role);
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'theme', 'date_start', 'date_start_str', 'duration_str'], 'required'],
            [['org_code'], 'string', 'max' => 5],
            [['org_code'], 'default', 'value' => '0000'],
            [['date_create', 'date_update', 'date_delete', 'duration'], 'integer'],            
            [['place'], 'string', 'max' => 100],
            [['theme'], 'string', 'max' => 500],           
            [['date_start_str'], 'match', 'pattern' => '/^\d{2}\.\d{2}\.\d{4} \d{2}:\d{2}/'],
            [['duration_str'], 'match', 'pattern' => '/^0[0-9]:[0-5][0-9]/'],            
            [['responsible', 'members_people', 'members_organization', 'note'], 'string'],
            [['date_start'], 'ruleDateStart'],
        ];
    }   

    /**
     * @inheritDoc
     */
    public function beforeValidate()
    {
        if (!parent::beforeValidate()) {
            return false;
        }
        $this->type = static::getType();
        return true;
    }  

    /**
     * Правило проверки пересечения мероприятия с другими мероприятиями
     * 
     * @param string $attribute
     */
    public function ruleDateStart($attribute)
    {
        $query = (new Query())
            ->from('{{%meeting}}')
            ->where("(:new_date_start1_1 >= {{date_start}} AND :new_date_start1_2 <= ({{date_start}} + {{duration}})) "
                . " OR ({{date_start}} >= :new_date_start2_1 AND {{date_start}} <= :new_date_start_and_duration) "
                . " AND {{date_delete}} IS NULL" , [
                ':new_date_start1_1' => $this->date_start,
                ':new_date_start1_2' => $this->date_start,
                ':new_date_start2_1' => $this->date_start,
                ':new_date_start_and_duration' => ((int)$this->date_start + (int)$this->duration),
            ]);

        if ($this->place) {
            $query->andWhere(['place' => $this->place]);
        }
        else {
            $query->andWhere('0=1');
        }

        if (!$this->isNewRecord) {
            $query->andWhere(['not', ['id' => $this->id]]);
        }
        $result = $query->one();
        if ($result) {
            $this->addError($attribute, sprintf('В данный период времени уже запланировано другое мероприятие "%s" в "%s".', 
                $result['theme'], Yii::$app->formatter->asDatetime($result['date_start'])));           
        }
    }    


}