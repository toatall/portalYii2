<?php
namespace app\models\thirty;

use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use app\models\Organization;

/**
 * 30-летие
 * @package app\models\thirty
 */
class ThirtyOldEmployee
{
    public $basePath = '/files_static/thirty/photos/30age/';
    public $pathOldEmployee = '/files_static/thirty/photos/30age/base.txt';
    private $base;
    private $organizations;

    /**
     * ThirtyOldEmployee constructor.
     * @param null $path
     * @throws HttpException
     */
    public function __construct($path = null)
    {
        if ($path != null) {
            $this->pathOldEmployee = $path;
        }
        $this->load();
    }

    /**
     * @throws HttpException
     */
    public function load()
    {
        $this->loadOrganizations();
        $this->base = [];

        $fullPath = \Yii::getAlias('@webroot') . $this->pathOldEmployee;
        if (!file_exists($fullPath)) {
            throw new HttpException(500, "File $fullPath not found!");
        }
        $file = file($fullPath);

        foreach ($file as $item) {
            $row = trim($item);
            if (strlen($row) > 0 && $row[0] <> '#') {
                //
                $arr = explode('|', $row);
                if (count($arr) >= 6) {
                    $this->base[$arr[0]] = [
                        'id' => $arr[0],
                        'fio' => $arr[1],
                        'fio_full' => $arr[2],
                        'code_org' => $this->getOrgByCode($arr[3]),
                        'file_name' => $this->basePath . $arr[4],
                        'file_name_thumb' => $this->basePath . 'thumb_' . $arr[4],
                        'description' => $arr[5],
                    ];
                }
            }
        }
    }

    /**
     * Случайная запись
     * @return |null
     */
    public function getRandomRecord()
    {
        $count = count($this->base);
        if ($count == 0) {
            return null;
        }
        $rand = rand(1, $count-1);
        return isset($this->base[$rand]) ? $this->base[$rand] : null;
    }

    /**
     * Поиск записи по ИД
     * @param $id
     * @return |null
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

    /**
     * Подгрузка организаций
     */
    protected function loadOrganizations()
    {
        $modelOrg = Organization::find()->all();
        $this->organizations = ArrayHelper::map($modelOrg, 'code', 'name');
    }

    /**
     * Наименование организации по ее коду
     * @param $code
     * @return mixed
     */
    protected function getOrgByCode($code)
    {
        return isset($this->organizations[$code]) ? $this->organizations[$code] : $code;
    }
}