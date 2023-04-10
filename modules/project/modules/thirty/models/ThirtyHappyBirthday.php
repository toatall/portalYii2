<?php
namespace app\modules\project\modules\thirty\models;

use app\models\json\JsonModel;
use Yii;

/**
 * А нам тоже 30 лет!
 * 
 * @property int $id
 * @property string $fio_full
 * @property string $org_code
 * @property string $photo
 */
class ThirtyHappyBirthday extends JsonModel
{

    /**
     * {@inheritdoc}
     */
    public static function getJsonFile(): string
    {
        return self::getBasePath() . 'base.json';
    }
    
    /**
     * @return string
     */
    protected static function getBasePath()
    {
        return Yii::getAlias('/files_static/thirty/photos/happy-birthday/');
    }
    

    /**
     * Фотография
     * @return string
     */
    public function getPhoto()
    {
        return self::getBasePath() . $this->photo;
    }

    /**
     * Поиск миниатюры
     * Имя должно начинаться с `thumb_`
     * @return string
     */
    public function getThumb()
    {
        $pathInfo = pathinfo(self::getBasePath() . $this->photo);    
        
        if (is_file(Yii::getAlias('@webroot') . $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'])) {
            return $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];
        }            
        return $this->getPhoto();
    }
    
}