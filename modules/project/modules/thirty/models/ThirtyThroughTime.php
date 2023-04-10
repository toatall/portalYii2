<?php
namespace app\modules\project\modules\thirty\models;

use app\models\json\JsonModel;
use Yii;

/**
 * Сквозь время
 * @package app\models\thirty
 * 
 * @property string $org_code
 * @property string $old_photo_img
 * @property string $old_photo_title
 * @property string $new_photo_img
 * @property string $new_photo_title
 */
class ThirtyThroughTime extends JsonModel
{

    /**
     * @return string
     */
    protected static function getBasePath()
    {
        return Yii::getAlias('/files_static/thirty/photos/through-time/');
    }
    
    /**
     * {@inheritdoc}
     */
    public static function getJsonFile(): string
    {
        return self::getBasePath() . 'base.json';
    }

    /**
     * Фотография старая
     * @return string
     */
    public function getPhotoOld()
    {
        return self::getBasePath() . $this->old_photo_img;
    }

    /**
     * Фотография новая
     * @return string
     */
    public function getPhotoNew()
    {
        return self::getBasePath() . $this->new_photo_img;
    }
    
}