<?php
namespace app\helpers;

use yii\helpers\FileHelper as HelpersFileHelper;

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

}