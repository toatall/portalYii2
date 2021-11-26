<?php

namespace app\components;

use Exception;
use Yii;

class LdapResult 
{
    private $_data;

    public function __construct($result)
    {
        $this->_data = $result;
    }
    
    /**
     * @return string
     */
    public function asText($field)
    {
        if (($item = $this->$field) === null) {
            return null;
        }
        
        if (isset($item[0])) {
            return $item[0];
        }
        return null;
    }

    public function asDateTime($field)
    {
        if (($item = $this->$field) === null) {
            return null;
        }
        
        if (isset($item[0])) {
            return Yii::$app->formatter->asDatetime($item[0] / 10000000-11644473600);
        }
        return null;
    }

    public function asArray($field)
    {
        if (($item = $this->$field) === null) {
            return null;
        }

        if (isset($item['count'])) {
            unset($item['count']);
        }
        return $item;
    }

    
    public function __get($name)
    {
        if (isset($this->_data[$name])) {
            $item = $this->_data[$name];
            return $item;
        }
        return null;
    }

    public function allData()
    {
        return $this->_data;
    }
   

}