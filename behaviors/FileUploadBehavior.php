<?php
namespace app\behaviors;

use yii\base\Behavior;
use yii\web\UploadedFile;

/**
 * Поведение для загрузки файлов
 * 
 * @author toatall <toatall.seek@gmail.com>
 */
class FileUploadBehavior extends Behavior
{
    
    /**
     * Каталог для сохранения файлов
     * @var string
     */
    public $uploadPath = 'upload';
    
    /**
     * Создавать миниатюры
     * @var bool
     */
    public $makeThumbs = true;
    
    /**
     * Ширина миниатюры
     * @var int
     */
    public $thumbWidth = 600;
    
    /**
     * Высота миниатюры
     * @var int
     */
    public $thumbHeight = 400;
    
    /**
     * Каталог с миниатюрами
     * @var string
     */
    public $thumbSubDir = '_thumb';
    
    
    /**
     * Загрузка файлов
     * @param string $attribute имя аттрибута поля с файлами
     */
    public function uploadFile($attribute, $beforeCallbak = null, $afterCallback = null)
    {
        $file = UploadedFile::getInstance($this->owner, $attribute);        
        if ($file) {
            if (is_callable($beforeCallbak)) {
                call_user_func($beforeCallbak);
            }
            
            $this->upload($file);
            
            if (is_callable($afterCallback)) {
                call_user_func($afterCallback);
            }
        }
    }
    
    /**
     * Загрузка файла
     * @param string $attribute имя аттрибута поля с файлом
     */
    public function uploadFiles($attribute, $beforeCallbak = null, $afterCallback = null)
    {
        $files = UploadedFile::getInstances($this->owner, $attribute);
        if ($files) {
            if (is_callable($beforeCallbak)) {
                call_user_func($beforeCallbak);
            }
            
            foreach($files as $file) {
                $this->upload($file);
            }
            
            if (is_callable($afterCallback)) {
                call_user_func($afterCallback);
            }
        }
    }
    
    /**
     * Создание миниатюры
     * @param UploadedFile $file
     */
    protected function makeThumbnail($file)
    {
        /** @var \app\components\Storage $storage */
        $storage = \Yii::$app->storage;            
        $sourcePath = $storage->mergePath($storage->getBasePath(), $this->uploadPath, $file->name);
        if ($this->makeThumbs && ($this->thumbHeight || $this->thumbWidth) && $this->isImage($sourcePath)) {
            $destPath = $storage->mergePath($storage->getBasePath(), $this->uploadPath, $this->thumbSubDir);
            $destImg = $storage->mergePath($destPath, $file->name);
            $this->resizeImage($sourcePath, $destPath, $destImg);
        }
    }
    
    /**
     * @param string $sourceImg
     * @param string $destImg
     */
    protected function resizeImage(string $sourceImg, string $destDir, string $destImg)    
    {
        if (\yii\helpers\FileHelper::createDirectory($destDir)) {
            if (!@\Yii::$app->storage->resizeImage($sourceImg, $this->thumbWidth, $this->thumbHeight, $destImg)) {
                @copy($sourceImg, $destImg);
            }
        }
    }
    
    
    /**
     * @param string $filename
     * @return bool
     */
    protected function isImage($filename)
    {        
        if (file_exists($filename)) {
            $mimeType = \yii\helpers\FileHelper::getMimeType($filename);
            return substr($mimeType, 0, 5) === 'image';
        }
        return false;
    }
    
    /**
     * Процесс загрузки файла
     * @param UploadedFile $file
     */
    protected function upload($file)
    { 
        if ($file instanceof \yii\web\UploadedFile) {
            \Yii::$app->storage->saveUploadedFile($file, $this->uploadPath);
            $this->makeThumbnail($file);
        }
    }
    
    /**
     * Очистка каталога
     * @param string $path
     * @return string
     */
    public function clearDir($path)
    {
        return \Yii::$app->storage->deleteDir($path);
    }
        
}