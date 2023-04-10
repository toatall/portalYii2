<?php
namespace app\models\json;

use Yii;
use yii\base\Exception;

/**
 * Базовый класс модели работы с json файлом
 * 
 * Обязательно требуется реализовать метод 
 * protected static function getJsonFile(): string
 * возвращающий путь к json-файлу
 * 
 * @example
 * Пример класса:
 * ```php
 * class SomeClass extends JsonModel
 * {
 *     protected static function getJsonFile(): string
 *     {
 *         return '/public/photos.json';
 *     }    
 * }
 * 
 * $model = new SomeClass();
 * $data = $model->find()->all();
 * ```
 * 
 * Пример json-файла:
 * ```json
 * {
 *     "items": [
 *         {
 *             "id": 1,
 *             "name": "Some text 1",
 *             "photo": "/images/photo1.jpg"
 *         },
 *         {
 *             "id": 2,
 *             "name": "Some text 2",
 *             "photo": "/images/photo3.jpg"
 *         }
 *     ]
 * }
 * ```
 * @author toatall
 * @version 2022-12-01
 * @see JsonQuery
 */
abstract class JsonModel
{
    /**
     * Данные
     * @var array
     */
    private $data;

    /**
     * @param array входящие данные
     */
    public function __construct($data = [])
    {
        $this->data = $data;
        $this->init();
    }

    /**
     * Функция инициализации
     */
    public function init() {}

    /**
     * @param string $name
     */
    public function __get($name)
    {
        return $this->data[$name] ?? null;        
    }    

    /**
     * Метод должен возвращать имя json-файла, содержащего данные
     * @return string
     */
    abstract protected static function getJsonFile(): string;

    /**
     * Загрузка данных из json-файла и преобразование в массив
     * Файл должен содержать родительский элемент с именем `items` или `data`
     * @return array|null
     */
    protected static function prepareData()
    {
        $file = Yii::getAlias('@webroot/' . ltrim(static::getJsonFile(), '\\/'));
        
        if (!is_file($file)) {
            throw new Exception("File $file not found!");
        }
        $json = json_decode(file_get_contents($file), true);
        if (!$json || !is_array($json)) {
            return null;
        }
        $firstKey = array_key_first($json);
        if (in_array($firstKey, ['items', 'data'])) {
            return $json[$firstKey];
        }
        else {
            return $json;
        }
    }

    /**
     * Создание экземпляра JsonQuery и передача ему данных
     * @return JsonQuery 
     */
    public static function find()
    {
        return new JsonQuery(static::prepareData(), get_called_class());
    }

    /**
     * Весь список аттрибутов 
     * @return array
     */
    public function attributeLabels()
    {
        return [];
    }

    /**
     * Поиск аттрибута по наименованию поля
     * @param string $name наименование поля
     * @return string|null
     */
    public function attributeLabelByName($name)
    {
        return $this->attributeLabels()[$name] ?? null;
    }

    
}