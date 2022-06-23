<?php

namespace app\modules\bookshelf\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\ChangeLogBehavior;
use app\behaviors\DatetimeBehavior;
use app\helpers\UploadHelper;
use app\models\User;
use Yii;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%book_shelf_what_reading}}".
 *
 * @property int $id
 * @property string $fio
 * @property string $writer
 * @property string $title
 * @property string $author
 * @property string $date_create
 * @property string $date_update
 * @property string $log_change
 *
 * @property User $authorModel
 */
class WhatReading extends \yii\db\ActiveRecord
{

    /**
     * @var yii\web\UploadedFile
     */
    public $uploadImage;

    /**
     * отметка об удалении изображения
     * @var boolean
     */
    public $deleteImage;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book_shelf_what_reading}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [            
            [
                'class' => DatetimeBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
            ],
            ['class' => AuthorBehavior::class],  
            ['class' => ChangeLogBehavior::class],         
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fio', 'writer', 'title'], 'required'],
            [['date_create', 'date_update'], 'safe'],
            [['log_change'], 'string'],
            [['fio', 'author'], 'string', 'max' => 250],
            [['writer'], 'string', 'max' => 500],
            [['title'], 'string', 'max' => 1000],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
            [['uploadImage'], 'file', 'skipOnEmpty' => true],
            [['deleteImage'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'fio' => 'ФИО',
            'writer' => 'Писатель',
            'title' => 'Название',
            'author' => 'Автор',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'log_change' => 'Журнал изменений',
            'deleteImage' => 'Удалить изображение',
        ];
    }

    /**
     * Gets query for [[AuthorModel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorModel()
    {
        return $this->hasOne(User::class, ['username' => 'author']);
    }


    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        $this->deleteImageFile($this->getImage());       
        $pathMain = Yii::getAlias('@webroot') . $this->getPathUploadFile();     
        if (is_dir($pathMain)) {
            FileHelper::removeDirectory($pathMain);
        }        
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (!$insert) {
            if ($this->deleteImage || $this->uploadImage) {
                $this->deleteImageFile($this->getImage());
            }
        }
    }
    



    /*** BEGIN UPLOAD IMAGE */

    /**
     * @return string
     */
    private function getPathUploadFile()
    {
        return str_replace('{id}', $this->id, Yii::$app->params['modules']['bookshelf']['uploadWhatReadingPhoto']);
    }

    /**
     * Загрузка изображения
     */
    public function upload()
    {
        (new UploadHelper($this->getPathUploadFile()))
            ->uploadFiles($this->uploadImage);
    }

    /**
     * Изображение
     * @return string[]
     */
    public function getImage()
    {
        return $this->prepareFile($this->getPathUploadFile());
    }

    /**
     * Подготовка файла
     * @param string
     * @return string
     */
    private function prepareFile($path)
    {        
        $files = $this->searchFiles(Yii::getAlias('@webroot') . $path);
        $result = null;
        if ($files && is_array($files) && count($files)) {           
            $result = $path . basename($files[0]);
        }
        return $result;
    }

    /**
     * Поиск файлов
     * @return string[]
     */
    private function searchFiles($path)
    {
        if (is_dir($path)) {
            return FileHelper::findFiles($path);
        }
        return null;
    }

    /**
     * имена файлов исполнения протоколов для удаления
     * если параметр $files не передается, то удаляются все файлы
     * @param string $files
     */
    public function deleteImageFile($file = null)
    {        
        if ($file != null) {
            FileHelper::unlink(Yii::getAlias('@webroot') . $file);
        }
    }    

    /*** END UPLOAD IMAGE */





    

}
