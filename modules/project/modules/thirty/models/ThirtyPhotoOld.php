<?php
namespace app\modules\project\modules\thirty\models;

use app\models\json\JsonModel;
use Yii;

/**
 * Мгновения службы
 * @package app\models\thirty
 * 
 * @property string $org_code
 * @property string $photo
 * @property string $title
 */
class ThirtyPhotoOld extends JsonModel
{

    /**
     * @return string
     */
    protected static function getBasePath()
    {
        return Yii::getAlias('/files_static/thirty/photos/instant-service/');
    }
    
    /**
     * {@inheritdoc}
     */
    public static function getJsonFile(): string
    {
        return self::getBasePath() . 'base.json';
    }

    /**
     * Фотография
     * @return string
     */
    public function getPhoto()
    {
        return self::getBasePath() . $this->org_code . '/' . $this->photo;
    }

    
}