<?php
namespace app\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use app\models\User;

/**
 * Журнал изменений
 * @author toatall
 */
class ChangeLogBehavior extends Behavior
{   
    // Операции
    const LOG_CREATE = 'создана';
    const LOG_UPDATE = 'изменена';
    const LOG_DELETE = 'удалена';
    
    // Формат разделителя
    
    // - разделитель между операций
    const DELIMITER_ACTION = '|';
    // - разделитель между значениями
    const DELIMITER_VALUE = ';';
    
    // - разделитель между операций
    const DELIMITER_ACTION_HTML = '<br />';
    // - разделитель между значениями
    const DELIMITER_VALUE_HTML = ' - ';
    
    
    /**
     * Аттрибут `Журнал изменений`
     * @var string 
     */
    public $change_log_at = 'log_change';
    
    /**
     * Аттрибут `Дата удаления`
     * @var string 
     */
    public $date_delete_at = 'deleted_at';
    
    /**
     * {@inheritdoc}
     */
    public function events(): array 
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'setValue',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'setValue',
        ];
    }        
    
    /**
     * Сохранение журнала
     */
    public function setValue()
    {     
        /** @var ActiveRecord $owner **/ 
        $owner = $this->owner;                     
        if ($owner->hasProperty($this->change_log_at))
        {
            $this->owner->{$this->change_log_at} = $this->setChangeLogValue((string)$this->owner->{$this->change_log_at});
        }
    }
    
    /**
     * @return string
     */
    public function getChangeLogHtml(): string
    {
        $resultHtml = '';
        $logArray = $this->getChangeLogArray();
        foreach ($logArray as $log)
        {
            if (isset($log['date']) && isset($log['operation']) && isset($log['author']))
            {                
                $resultHtml .= '<i class="glyphicon glyphicon-calendar"></i> ' 
                    . $log['date'] . self::DELIMITER_VALUE_HTML 
                    . $log['operation'] . self::DELIMITER_VALUE_HTML 
                    . $log['author'] . self::DELIMITER_ACTION_HTML;
            }
        }
        return $resultHtml;
    }
    
    /**
     * @return array
     */
    public function getChangeLogArray(): array
    {
        /** @var ActiveRecord $owner **/ 
        $owner = $this->owner;
        
        $resultArray = array();
        
        $arr = explode(self::DELIMITER_ACTION, $owner->{$this->change_log_at});
        foreach ($arr as $a)
        {
            $values = explode(self::DELIMITER_VALUE, $a);
            if (is_array($values) && count($values)==3)
            {
                $resultArray[] = [
                    'date' => $values[0],                    
                    'author' => $values[1],
                    'operation' => $values[2],
                ];
            }
        }        
        return $resultArray;
    }
    
    /**
     * @return string
     */
    private function getAuthor()
    {
        return User::getUsername();
    }
    
    /**
     * @param string $logPast
     * @return string
     */
    private function setChangeLogValue(string $logPast)
    {        
        if ($logPast===null)
        {
            $logPast = '';
        }
        
        return $logPast 
                . $this->getDateTime() . self::DELIMITER_VALUE 
                . $this->getAuthor() . self::DELIMITER_VALUE
                . $this->getOperation() . self::DELIMITER_ACTION;                
    }    
    
    /**
     * @return string
     */
    private function getDateTime()
    {
        return \Yii::$app->formatter->asDatetime(time());
    }
    
    /**
     * @return string
     */
    private function getOperation()
    {
        /** @var ActiveRecord $owner **/ 
        $owner = $this->owner;
        if ($owner->isNewRecord)
        {
            return self::LOG_CREATE;
        }
        elseif ($owner->hasAttribute($this->date_delete_at) && !empty($owner->{$this->date_delete_at}))
        {
            return self::LOG_DELETE;
        }
        else
        {
            return self::LOG_UPDATE;
        }
    }
}
