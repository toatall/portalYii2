<?php

namespace app\models;

use app\components\Storage;
use Yii;

/**
 * This is the model class for table "{{%image}}".
 *
 * @property int $id
 * @property int $id_model
 * @property string $model
 * @property string $image_name
 * @property string|null $image_name_thumbs
 * @property int|null $image_size
 * @property string $date_create
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%image}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['image_name'], 'required'],
            [['id_model', 'image_size'], 'integer'],
            [['date_create'], 'safe'],
            [['model'], 'string', 'max' => 50],
            [['image_name', 'image_name_thumbs'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_model' => 'Id Model',
            'model' => 'Model',
            'image_name' => 'Image Name',
            'image_name_thumbs' => 'Image Name Thumbs',
            'image_size' => 'Image Size',
            'date_create' => 'Date Create',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function afterDelete()
    {
        /* @var $storage Storage */
        $storage = Yii::$app->storage;

        // удаление основного изображения
        $storage->deleteFile($this->image_name);

        // удаление миниатюры
        $storage->deleteFile($this->image_name_thumbs);

        parent::afterDelete();
    }

}
