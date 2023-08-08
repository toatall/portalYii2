<?php

namespace app\models\telephone;

use stdClass;
use Yii;
use yii\db\Query;

class TelephoneSOAP
{

    /**
     * Изображение сотрудника по-умолчанию
     */
    const DEFAULT_PERSON_IMG = '/img/user-default.png';

    /**
     * SOAP link
     * @var mixed
     */
    private $soap;

    /**
     * {@inheritdoc}
     */
    public function __construct($url, $soapOptions = [])
    {
        if (!$this->soap) {
            $this->soap = new \SoapClient($url, $soapOptions);
        }
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
     * Вывод всей структуры организации
     * @return array
     */
    public function getStructureOrg($unid)
    {
        if ($unid == null) {
            return null;
        }        
        $results = $this->soap->getAllStructByOrganization($unid);        
        $prepare = $this->prepareValues($results);
        $prepare = $prepare['childs'] ?? $prepare; // удалить организациию из массива
        $prepare = $this->clearData($prepare); // очистка данных
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
        $prepare = $this->clearData($prepare);
        if (is_array($prepare) && count($prepare) > 0
            && isset($prepare[0]) && is_array($prepare[0]) && count($prepare[0])) {
                $sort = $this->arraySortByValue($prepare, 'parentOrgCode');
        }
        else {
            $sort = $prepare;
        }
        return $sort;
    }    

    /**
     * Получение изображения сотрудника по его ФИО
     * @param string $fio
     * @param string $orgCode
     * @return string     
     */
    public static function getPhotoByFio($fio, $orgCode)
    {
        $cacheName = 'telephone.photo' . $orgCode . '.' . $fio;
        $cache = Yii::$app->cache;
        $photo = $cache->get($cacheName);
        
        if ($photo === false) {            
            $photo = self::findImageInDb($fio, $orgCode);        
            $cache->set($cacheName, $photo, 30*60);
        }

        return $photo;
    }

    /**
     * Поиск изображения в БД
     * 1. личной карточки сотрудника
     * 2. структуры отдела
     * @param string $fio
     * @param string $orgCode
     * @return string
     */
    private static function findImageInDb($fio, $orgCode)
    {
        // поиск фотки в профиле пользователя
        $query = (new Query())
            ->from('{{%user}}')
            ->where("REPLACE(fio,'ё','е') = REPLACE(:fio,'ё','е')", [
                ':fio' => $fio,
            ])
            ->andFilterCompare('default_organization', $orgCode)
            ->one();
            
        if ($query && $query['photo_file']) {
            $img = Yii::getAlias('@webroot') . $query['photo_file'];  
            if (is_file($img) && file_exists($img)) {
                return $query['photo_file'];
            }
        }

        // поиск в структуре отдела
        $query = (new Query())
            ->from('{{%department_card}} card')
            ->innerJoin('{{%department}} dep', 'card.id_department=dep.id')
            ->where("REPLACE(card.user_fio,'ё','е') = REPLACE(:fio,'ё','е')", [
                ':fio' => $fio,
            ])
            ->andFilterCompare('dep.id_organization', $orgCode)
            ->one();
        
        if ($query && $query['user_photo']) {
            $img = Yii::getAlias('@webroot') . $query['user_photo'];            
            if (is_file($img) && file_exists($img)) {
                return $query['user_photo'];
            }
        }

        return self::DEFAULT_PERSON_IMG;
    }

    

    //---------- < ФУНКЦИИ ПРЕОБРАЗОВАНИЯ > ----------//

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

    /**
     * Очистка данных 
     * - удаление сотрудников без номеров телефонов
     * - удаление пустых отделов (без сотрудников)
     * @param array $data массив данных
     * @return array
     */
    private function clearData($data)
    {        
        if (!$data) {
            return null;
        }

        // если это отдельная запись (отдел, сотрудник)
        if (isset($data['type'])) {
            // проверка сотрудника
            if ($data['type'] == 'person') {
                // должен быть заполнен хотя бы один телефон
                if (empty($data['personTel1']) && empty($data['personTel2'])) {
                    return null;
                }
                // поиск фотографии сотрудника
                $data['photo'] = $this->getPhotoByFio($data['personFullName'], $data['parentOrgCode']);
            }
            
            // отдел
            if ($data['type'] == 'dep') {
                if (isset($data['childs']) && $data['childs']) {
                    $child = $this->clearData($data['childs']);
                    if (!$child) {
                        return null;
                    } 
                    $data['childs'] = $child;
                }
            }

            return $data;
        }
        else {
            $result = [];
            // проход по массиву записей
            foreach($data as $item) {
                $resItem = $this->clearData($item);
                if ($resItem) {
                    $result[] = $resItem;
                }
            }
            return $result;
        }
    }    

    //---------- < / ФУНКЦИИ ПРЕОБРАЗОВАНИЯ > ----------//


}