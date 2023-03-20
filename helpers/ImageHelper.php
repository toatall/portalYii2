<?php
namespace app\helpers;

use yii\helpers\FileHelper as HelpersFileHelper;
use Yii;

/**
 * Поиск только файлов-изображений
 * png|jpg|jpeg|gif|bmp|svg|jfif|webp
 * @author toatall
 */
class ImageHelper extends HelpersFileHelper
{

    /**
     * Returns the images found under the specified directory and subdirectories.
     * @param string $dir Full path
     * @param array $options 
     * @return array
     */
    public static function findImages($dir, $options = [])
    {
        return parent::findFiles($dir, array_merge([
            'filter' => function(string $filename):bool {               
                $mimeType = \yii\helpers\FileHelper::getMimeType($filename);
                return substr($mimeType, 0, 5) === 'image';
            },
        ], $options));
    }
    
    /**
     * Поиск миниатюры
     * Предполагается, что миниатюра лежит в подпапке _thumb, 
     * где лежит полноразмерный файл изображения
     * Если миниатюра не найдена, то будет возвращено переданное имя
     * 
     * @param string $imagePath
     * @param string $thumbPath
     * @return string
     */
    public static function findThumbnail($imagePath, $thumbPath = '_thumb', $picImageNotFound = '')
    {
        /** @var \app\components\Storage $storage */
        $storage = Yii::$app->storage;
        
        if (!$imagePath || !file_exists($storage->mergeUrl(Yii::getAlias('@webroot'), $imagePath))) {
            return $picImageNotFound;
        }
        $thumbImage = str_replace(basename($imagePath), $storage->mergeUrl($thumbPath, basename($imagePath)), $imagePath);
        if (file_exists(Yii::getAlias('@webroot') . $thumbImage)) {
            return $thumbImage;
        }
        return $imagePath;
    }

}