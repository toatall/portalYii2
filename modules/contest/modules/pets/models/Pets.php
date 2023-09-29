<?php

namespace app\modules\contest\modules\pets\models;

use app\helpers\UploadHelper;
use app\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%contest_pets}}".
 *
 * @property int $id
 * @property string $pet_name
 * @property string $pet_owner
 * @property string|null $pet_age
 * @property int $date_create
 * 
 * @property User $owner
 */
class Pets extends \yii\db\ActiveRecord
{

    /**
     * @var yii\web\UploadedFile[]
     */
    public $uploadFiles;

    /**
     * Файлы отмеченные для удаления
     * @var string[]
     */
    public $deleteFiles;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contest_pets}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [            
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => false,
            ],  
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pet_name', 'pet_owner'], 'required'],
            [['date_create'], 'integer'],
            [['pet_name', 'pet_owner'], 'string', 'max' => 250],
            [['pet_age'], 'string', 'max' => 255],
            [['uploadFiles'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 30],
            [['deleteFiles'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'pet_name' => 'Кличка',
            'pet_owner' => 'Хозяин',
            'pet_age' => 'Возраст животного',
            'date_create' => 'Дата создания',
            'uploadFiles' => 'Изображения',
            'deleteFiles' => 'Отметьте изображения для удаления',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        $this->deleteFiles(null, true);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if (!$insert) {
            $this->deleteFiles($this->deleteFiles);
        }
    }

    public function getOwner()
    {
        return $this->hasOne(User::class, ['username' => 'pet_owner']);
    }



    /** UPLOAD FILES */

    protected function getPathFiles()
    {
        return str_replace('{id}', $this->id, '/public/upload/contest/{id}/');
    }
    
    /**
     * Upload files
     */
    public function upload()
    {   
        (new UploadHelper($this->getPathFiles()))
            ->uploadFiles($this->uploadFiles);       
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        $path = $this->getPathFiles();
        $files = $this->searchFiles(Yii::getAlias('@webroot') . $path);
        $result = [];
        if ($files && is_array($files) && count($files)) {
            foreach($files as $file) {
                $result[] = $path . basename($file);
            }
        }
        return $result;
    }

    /**
     * Поиск файлов
     * @return string[]|null
     */
    private function searchFiles($path)
    {
        if (is_dir($path)) {
            return FileHelper::findFiles($path);
        }
        return null;
    }

    /**
     * Delete files from the disk
     * @param array|null $files
     */
    public function deleteFiles($files = null, $deleteAll = false)
    {
        if ($deleteAll) {
            $files = $this->getFiles();
        }
        if ($files) {
            foreach ($files as $file) {
                FileHelper::unlink(Yii::getAlias('@webroot') . $file);
            }
        }
        if ($deleteAll) {
            FileHelper::removeDirectory($this->getPathFiles());
        }
    }





}
