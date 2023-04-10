<?php
namespace app\modules\project\modules\thirty\models;

use app\models\json\JsonModel;
use Yii;

/**
 * @property int $id
 * @property string $fio_short
 * @property string $fio_full
 * @property string $code
 * @property string $photo
 * @property string $description
 */
class ThirtyOldEmployee extends JsonModel
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
        return Yii::getAlias('/files_static/thirty/photos/30age/');
    }

   
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'fio_short' => 'ФИО сокращенно',
            'fio_full' => 'ФИО',
            'org_code' => 'Код организации',
            'photo' => 'Файл',
            'description' => 'Описание',            
        ];
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        return self::getBasePath() . $this->photo;
    }

    public function getThumb()
    {
        $fullPath = Yii::getAlias('@webroot') . self::getBasePath() . 'thumb_' . $this->photo;
        if (is_file($fullPath)) {
            return self::getBasePath() . 'thumb_' . $this->photo;
        }
        return $this->getPhoto();
    }

}