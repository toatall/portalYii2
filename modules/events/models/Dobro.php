<?php

namespace app\modules\events\models;

/**
 * Description of Dobro
 *
 * @author toatall
 */
class Dobro 
{
    /**
     * Данные
     * @var array
     */
    protected $_data;
    
    /**
     * 
     */
    public function __construct() 
    {
        $this->loadData();
    }
    
    /**
     * @throws \Exception
     */
    protected function loadData()
    {
        $root = \Yii::getAlias('@webroot');
        $db = $root . $this->getPathJsonDb();
        if (!file_exists($db)) {
            throw new \Exception("Файл {$db} не найден!");
        }
        $fileData = file_get_contents($db);
        $this->_data = json_decode($fileData, true);
    }
    
    /**
     * @return string
     */
    protected function getPathJsonDb()
    {
        return \Yii::$app->controller->module->params['dobro']['json_db'];
    }
   
    /**
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }
    
}
