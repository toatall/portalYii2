<?php
namespace app\models\thirty;

use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use app\models\Organization;

/**
 * Сквозь время
 * @package app\models\thirty
 */
class ThirtyHappyBirthday
{
    protected $basePath = '/files_static/thirty/photos/happy-birthday/';
    public $baseFile = '/files_static/thirty/photos/happy-birthday/base.txt';
    private $base = [];
    private $organizations;

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
        $this->loadOrganizations();
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
                if (count($arr) >= 4) {
                    $this->base[$arr[0]] = [
                        'id' => $arr[0],
                        'code_ifns' => $this->getOrgByCode($arr[1]),
                        'photo' => $this->basePath . $arr[2],
                        'description' => $arr[3],
                    ];
                }
            }
        }
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