<?php
namespace app\models\thirty;

use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use app\models\Organization;

/**
 * Сквозь время
 * @package app\models\thirty
 */
class ThirtyThroughTime
{
    protected $basePath = '/files_static/thirty/photos/through-time/';
    public $baseFile = '/files_static/thirty/photos/through-time/base.txt';
    private $base = [];

    /**
     * ThirtyThoughTime constructor.
     * @param null $path
     * @throws HttpException
     */
    public function __construct($path = null)
    {
        if ($path != null) {
            $this->basePath = $path;
        }
        $this->load();
    }

    /**
     * @throws HttpException
     */
    public function load()
    {
        $this->base = [];

        $fullPath = \Yii::getAlias('@webroot') . $this->baseFile;
        if (!file_exists($fullPath)) {
            throw new HttpException(500, "File $fullPath not found!");
        }
        $file = file($fullPath);

        foreach ($file as $item) {
            $row = trim($item);
            if (strlen($row) > 0 && $row[0] <> '#') {
                // id|date|code_ifns|photo_old|photo_new|description_old|description_new
                $arr = explode('|', $row);
                if (count($arr) >= 7) {
                    $this->base[$arr[0]] = [
                        'id' => $arr[0],
                        'date' => $arr[1],
                        'code_ifns' => $arr[2],
                        'photo_old' => $this->basePath . $arr[3],
                        'photo_new' => $this->basePath . $arr[4],
                        'description_old' => $arr[5],
                        'description_new' => $arr[6],
                    ];
                }
            }
        }
    }

    /**
     * Запись для сегодня
     * @return mixed|null |null
     */
    public function getTodayRecord()
    {
        $count = count($this->base);
        if ($count == 0) {
            return null;
        }
        foreach ($this->base as $item) {
            if ($item['date'] == date('d.m.Y')) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Поиск записи по ИД
     * @param $id
     * @return mixed|null |null
     */
    public function findById($id)
    {
        return isset($this->base[$id]) ? $this->base[$id] : null;
    }

    /**
     * @return mixed
     */
    public function findAll()
    {
        return $this->base;
    }
}