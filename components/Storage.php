<?php
namespace app\components;

use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * Description of FileUploadHelper
 *
 * @author toatall
 */
class Storage extends \yii\base\Component 
{
    /**
     * Путь к каталогу с файлами на стороне frontend
     * @var string 
     */
    public $basePath = '@webroot';
    
    /**
     * Ссылка на сайт frontend
     * @var string 
     */
    public $baseUrl = '@web';
    
    /**
     * Использовать преобразование имени файла 
     * @var boolean
     */
    public $useCharset = true;
    
    /**
     * Преобразовать имя файла из кодировки
     * @var string
     */
    public $fromCharset = 'utf-8';
    
    /**
     * Преобразовать имя файла в кодировку
     * @var string
     */
    public $toCharset = 'windows-1251';
    
    /**
     * Каталог для сохранения (по умолчанию)
     * @var string
     */
    public $aliasPathDefault = 'upload';
    
    /**
     * Ссылка на сохраненный файл (по умолчанию)
     * @var string
     */
    public $aliasUrlDefault = 'uploadUrl';
    
    /**
     * Имя файла
     * @var \yii\web\UploadedFile
     */
    private $fileName;
    
    /**
     * Каталог для сохранения (указанный пользователем)
     * @var string
     */
    private $aliasPath; 
    

    /**
     * Save given UploadedFile instance to disk
     * @param UploadedFile $file
     * @param string $alias
     * @param bool $autoGenerateName
     * @return string|null
     * @throws \yii\base\Exception
     */
    public function saveUploadedFile(UploadedFile $file, $alias = '', $autoGenerateName = false)
    {
        $this->aliasPath = $alias;
        $path = $this->preparePath($file);
        if ($autoGenerateName) {
            $path = $this->generateFileName($path);
        }
        
        if ($path && $file->saveAs($path)) {
            return $path;
        }
    }

    /**
     * Подготовка каталога для сохранения файла
     * @param UploadedFile $file
     * @return string|null
     * @throws \yii\base\Exception
     */
    protected function preparePath(UploadedFile $file)
    {
        $this->fileName = $file;
        $path = $this->addEndSlash($this->getStoragePath()) . $this->fileName;
        $path = $this->mergePath($this->getBasePath(), $path);
        
        if (FileHelper::createDirectory(dirname($path))) {
            return $this->convertCharset($path);            
        }
    }
    
    /**
     * Измнение кодировки файла
     * @param string $path
     * @return string
     */
    protected function convertCharset(string $path)
    {
        if ($this->useCharset) {
            return iconv($this->fromCharset, $this->toCharset, $path);
        }
        return $path;        
    }    
    
    /**
     * Получение каталога для сохранения файла
     * @return string
     */
    public function getStoragePath()
    {        
        if (empty($this->aliasPath)) {
            return \Yii::getAlias(\Yii::$app->params[$this->aliasPathDefault]);
        }
        return \Yii::getAlias($this->aliasPath);
    }
    
    /**
     * @param string $filename
     * @return string
     */
    public function getFileUrl($filename) 
    {
        return $this->mergeUrl($this->getBaseUrl(), $filename);
    }
    
    /**
     * @param string $filename
     * @return string
     */
    public function getFile(string $filename)
    {
        return $this->mergePath($this->getBasePath(), $filename);
    }
    
    /**
     * Подготовка ссылки
     * @param string $alias
     * @return string
     */
    protected function prepareUrl(string $alias)
    {
        if (empty($alias)) {
            return $this->normalizeUrl(\Yii::$app->params[$this->aliasUrlDefault]);
        }
        return $this->normalizeUrl(\Yii::getAlias($alias));
    }
    
    /**
     * @param string $url
     * @return string
     */
    protected function normalizeUrl(string $url)
    {
        return $this->addEndSlash(\Yii::getAlias($url));
    }
    
    /**
     * Добавление завершающего слеша
     * @param string $url
     * @return string
     */
    public function addEndSlash(string $url): string
    {
        return rtrim($url, '/') . '/';
    }
    
    /**
     * @return string
     */
    public function getBasePath()
    {
        return \Yii::getAlias($this->basePath);
    }
    
    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return \Yii::getAlias($this->baseUrl);
    }

    /**
     * Объединение частей пути
     * @param mixed $path 
     * @return string
     */
    public function mergePath(...$paths)
    {
        $path = implode(DIRECTORY_SEPARATOR, $paths);
        return FileHelper::normalizePath($path);
    }
    
    /**
     * Объединение двух частей ссылки
     * @param string $url1
     * @param string $url2
     * @return string
     */
    public function mergeUrl($url1, $url2)
    {
        $ds = '/';
        return rtrim($url1, $ds) . $ds . ltrim($url2, $ds);
    }

    /**
     * Изменение размера изображения
     * @return boolean
     */
    public function resizeImage(string $imagePath, $width, $height, $newName = '')
    {        
        if (file_exists($imagePath)) {
            $imagine = new \Imagine\Gd\Imagine();
            $size = new \Imagine\Image\Box($width, $height);
            $imagine->open($imagePath)
                    ->thumbnail($size)
                    ->save($newName ? $newName : $imagePath);
            return true;
        }
        return false;
    }

    /**
     * Удаление файла
     * @param string $file имя относительно папки каталога @web
     * @return boolean
     */
    public function deleteFile(string $file)
    {        
        $fullName = $this->mergePath($this->getBasePath(), $file);
        $mergeFullName = $this->convertCharset($fullName);
        
        if (file_exists($mergeFullName)) {
            if (FileHelper::unlink($mergeFullName)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Удаление каталога
     * @param string $path
     * @return bool
     */
    public function deleteDir(string $path)
    {
        $fullPath = $this->mergePath($this->getBasePath(), $path);
        if (is_dir($fullPath)) {
            if (FileHelper::removeDirectory($fullPath)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Добавление к имени префикса
     * @param string $fullName
     * @param string $prefix
     * @return string
     */
    public function addFileNamePrefix(string $fullName, string $prefix)
    {
        $baseName = basename($fullName);
        return str_replace($baseName, $prefix . '_' . $baseName, $fullName);
    }
    
    /**
     * Добавление постфикса к имени файла
     * @param string $fullName
     * @param string $postfix
     * @return string
     */
    public function addFileNamePostfix(string $fullName, string $postfix)
    {
        $info = pathinfo($fullName);      
        return $this->mergePath($info['dirname'], $info['basename'] . $postfix . '.' . $info['extension']);
    }


    /**
     * Размер файла текстом
     * @param int $size
     * @param int $decimal
     * @return string
     */
    public function sizeText(int $size, $decimal = 2)
    {
        $sizes = [
            0 => 'байт',
            1 => 'КБ',
            2 => 'МБ',
            3 => 'ГБ',
            4 => 'ТБ',
        ];
        $factor = floor((strlen($size) - 1) / 3);
        return sprintf("%.{$decimal}f", ($size / pow(1024, $factor))) . ' ' . (isset($sizes[$factor]) ? $sizes[$factor] : '-');
    }
    
    /**
     * Размер файла
     * @param string $fileName
     * @return int
     */
    public function size($fileName)
    {   
        $fileName = $this->convertCharset($fileName);
        
        if (file_exists($fileName)) {
            return filesize($fileName);
        }        
        return 0;
    }
    
    /**
     * Генерирование имени
     * @param string $fileName
     * @return string
     */
    public function generateFileName($fileName)
    {
        $info = pathinfo($fileName);      
        return $this->mergePath($info['dirname'], uniqid() . '.' . $info['extension']);
    }
    
}
