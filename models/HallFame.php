<?php

namespace app\models;


/**
 * Class HallFame
 * @package app\models
 */
class HallFame
{
    /**
     * Год
     * @var string
     */
    private $year;

    /**
     * Файлы изображений
     * @var array
     */
    private $files;

    /**
     * Список годов
     * @var array
     */
    private $years = [];

    /**
     * Поиск изображений за указанный период и вывод их в виде списка
     * @param string $year год
     */
    public function __construct($year = null)
    {
        if (!$this->scanDirYears())
            return false;

        $this->year = $year;

        // определение периода
        if (empty($year)) {
            $this->year = date('Y') - 1;
        }
        elseif (!isset($this->years[$this->year])) {
            $this->year = 'default';
        }

        // поиск файлов
        $this->loadFiles($this->year);
    }

    /**
     * Поиск каталогов с годами
     * @return boolean
     * @uses __construct()
     */
    private function scanDirYears()
    {
        $path = $this->getPath();

        if (file_exists($path)) {
            $dh = opendir($path);
            while (false !== ($filename = readdir($dh))) {
                if ($filename === '.' || $filename === '..') {
                    continue;
                }

                if (is_dir($path . $filename)) {
                    $this->years[$filename] = $filename;
                }
            }
        }
        return (count($this->years) > 0);
    }

    /**
     * @return mixed
     */
    private function getPath()
    {
        return \Yii::getAlias('@webroot') . \Yii::$app->params['hallFame']['path'];
    }

    private function getUrl()
    {
        return \Yii::$app->params['hallFame']['path'];
    }

    /**
     * Список изображений
     * @return array
     * @uses SiteController:actionHallFame()
     */
    public function showPhoto()
    {
        return $this->files;
    }

    /**
     * @return integer
     */
    public function getInterval()
    {
        return \Yii::$app->params['hallFame']['intervalChangeImages'];
    }

    /**
     * Поиск изображений по маске $this->dateFilter
     * Если не удолось найти изображение, то нужно подгрузить файлы
     */
    private function loadFiles($year)
    {
        $this->files = []; // очистка файлов
        $pathImages = $this->getPath();

        if (!file_exists($pathImages . $year . '/')) {
            $year = 'default/';
        }

        $path = $pathImages . $year . '/'; // формирование пути

        if (file_exists($path)) {
            $dh = opendir($path);
            while (false !== ($filename = readdir($dh))) {
                if ($filename === '.' || $filename === '..') {
                    continue;
                }

                $ext = explode('.', $filename);
                if (is_array($ext)) {
                    $ext = end($ext);
                }

                if (in_array(strtoupper($ext), $this->getExtensions())) {
                    $this->files[] = [
                        'src' => $this->getUrl() . $year . "/$filename",                        
                    ];
                }
            }
        }
        else {
            //exit($path);
        }
    }

    /**
     * @return mixed
     */
    private function getExtensions()
    {
        return \Yii::$app->params['hallFame']['extensionImages'];
    }

    /**
     * Список годов
     * @return array
     * @uses SiteController::actionHallFame()
     */
    public function getYears()
    {
        return $this->years;
    }

    /**
     * Год
     * @return string
     * @uses SiteController::actionHallFame()
     */
    public function getYear()
    {
        return $this->year;
    }
}