<?php
namespace app\helpers;

use yii\helpers\FileHelper as HelpersFileHelper;


class ImageHelper extends HelpersFileHelper
{

    /**
     * Returns the images found under the specified directory and subdirectories.
     * @return array
     */
    public static function findImages($dir, $options = [])
    {
        return parent::findFiles($dir, array_merge([
            'filter' => function(string $path):bool {
                return preg_match('/((\.png)|(\.jpg)|(\.jpeg)|(\.gif)|(\.bmp)|(\.svg)|(\.jfif)|(\.webp))$/i', $path);
            },
        ], $options));
    }

}