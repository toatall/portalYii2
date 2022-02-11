<?php
namespace app\modules\kadry\models;

use yii\base\Exception;
use yii\base\Model;

/**
 * Обучающие материалы
 * --------------------
 * 
 * В файле params.php (modules/kadry/education/jsonFile) указывается путь к файлу json со следующими данными:
 * {
 *      "data": [
 *          {
 *              "id": 1, // порядковый номер 1,2,3...
 *              "title": "Заголовок матерала", 
 *              "description": "Краткое описание",
 *              "thumbnail_image": "Миниатюра",
 *              "data": {
 *                  "text": "Описание",
 *                  "parts": [
 *                      {
 *                          "name": "Название части",
 *                          "files": [
 *                              {
 *                                  "path": "path/file1",
 *                                  "name": "Наименование 1",
 *                                  "type": "Тип файла: doc|docx|xls|xlsx|pdf|txt|video|other"
 *                              }
 *                              ...
 *                          ],
 *                          "parts": [...]
 *                      }
 *                      ...
 *                  ]
 *              }
 *          }
 *      ]
 * }
 * 
 * 
 * 
 */
class Education extends Model
{    

    /**
     * Json-данные из файла
     */
    private $_data;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->initData();
    }

    /**
     * Файл с данными по материалам обучения
     */
    protected function getJsonFile()
    {
        return \Yii::getAlias('@webroot') . \Yii::$app->params['modules']['kadry']['education']['jsonFile'] ?? null;
    }

    /**
     * Инициализация (подключение файла json)
     */
    protected function initData()
    {
        $file = $this->getJsonFile();
        if (!file_exists($file)) {
            throw new Exception("Файл $file не найден!");
        }
        $this->_data = json_decode(file_get_contents($file));
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->_data->items;
    }

    /**
     * Поиск записи по идентификатору
     * @param int $id
     * @return array|null
     */
    public static function findById($id)
    {
        $model = new self();
        foreach ($model->getData() as $item) {
            if ($id == $item->id) {
                return $item;
            }
        }
        return null;
    }

    

}