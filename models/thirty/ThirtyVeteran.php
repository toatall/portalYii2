<?php
namespace app\models\thirty;

use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use app\models\Organization;

/**
 * 30-летие
 * @package app\models\thirty
 */
class ThirtyVeteran
{
    public $basePath = '/files_static/thirty/photos/veteran/';
    public $pathOldEmployee = '/files_static/thirty/photos/veteran/base.txt';
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
            if (preg_match('/^[^#]/', trim($row))) {
                //
                $arr = explode('|', $row);
                if (count($arr) >= 5) {
                    $this->base[$arr[0]][] = [
                        'id' => $arr[0],
                        'code_ifns' => $this->getOrgByCode($arr[1]),
                        'file_name' => $this->basePath . $arr[2],
                        'file_name_thumb' => $this->basePath . $arr[3],
                        'description' => $arr[4],
                    ];
                }
            }
        }
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
    public function getOrgByCode($code)
    {
        return isset($this->organizations[$code]) ? $this->organizations[$code] : $code;
    }
}