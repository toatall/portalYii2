<?php

namespace app\models\telephone;

use stdClass;
use Yii;


class TelephoneSOAP
{
    /**
     * SOAP link
     * @var mixed
     */
    private $soap;


    public function __construct()
    {
        if (!$this->soap) {
            $this->soap = new \SoapClient($this->getSOAPServiceUrl(), []);
        }
    }

    /**
     * @return string
     */
    public function getSOAPServiceUrl()
    {
        return Yii::$app->params['telephone']['SOAPServiceUrl'];
    }   

    /**
     * Поиск документа по unid
     * @return
     */
    public function getDocByUnid($unid)
    {
        if (!$unid) {
            return null;
        }
        
        $result = $this->soap->getDocByUnid($unid);
        $prepare = $this->prepareValues($result);
        return $prepare;
    }

    /**
     * @return array
     */
    public function getStructureOrg($unid)
    {
        if ($unid == null) {
            return null;
        }        
        $results = $this->soap->getAllStructByOrganization($unid);        
        $prepare = $this->prepareValues($results);
        $prepare = $prepare['childs'] ?? $prepare;
        $sorted = $this->arraySortByValue($prepare, 'sortIndex');
        return $sorted;
    }

    /**
     * Поиск по тексту
     * @param string $term
     * @return mixed
     */
    public function search($term) 
    {
        $results = $this->soap->find($term);       
        $prepare = $this->prepareValues($results);    
        if (is_array($prepare) && count($prepare) > 0
            && isset($prepare[0]) && is_array($prepare[0]) && count($prepare[0])) {
                $sort = $this->arraySortByValue($prepare, 'parentOrgCode');
        }
        else {
            $sort = $prepare;
        }
        return $sort;
    }    

    //---------- НАЧАЛО. ФУНКЦИИ ПРЕОБРАЗОВАНИЯ ----------//

    /**
     * Преобразование stdClass в array
     * @param array $soapData
     * @return array
     */
    private function prepareValues($soapData)
    {
        if ($soapData instanceof stdClass) {
            if (isset($soapData->item)) {
                $soapData = $soapData->item;
            }
            else {
                $soapData = get_object_vars($soapData);
            }
        }
        $soapData = $this->objectToArray($soapData);
              
        return $soapData;    
    }

    /**
     * Преобразование stdClass в array
     * @param array $obj
     * @return array
     */
    private function objectToArray($obj) 
    {
        if (is_object($obj)) {
            $obj = (array)$obj;
        }
        if (is_array($obj)) {
            $new = [];
            foreach ($obj as $key => $item) {
                $new[$key] = $this->objectToArray($item);
            }
        }
        else {
            $new = $obj;
        }
        return $new;
    }

    /**
     * Сортровка по значению подмассива
     * @param array $data массив
     * @param string $keyName ключ в подмассиве, 
     * по значению которого будет выполнена сортировка
     * @return array
     */
    private function arraySortByValue(&$data, $keyName)
    {
        if (!is_array($data)) {
            return $data;
        }
        usort($data, function($a, $b) use ($keyName) {                               
            if ($a[$keyName] == $b[$keyName]) {
                return 0;
            }
            return ($a[$keyName] < $b[$keyName]) ? -1 : 1;
        });
        return $data;
    }

    //---------- КОНЕЦ. ФУНКЦИИ ПРЕОБРАЗОВАНИЯ ----------//

}