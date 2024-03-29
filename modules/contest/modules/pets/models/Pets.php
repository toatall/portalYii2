<?php

namespace app\modules\contest\modules\pets\models;

use app\helpers\UploadHelper;
use app\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%contest_pets}}".
 *
 * @property int $id
 * @property string $pet_name
 * @property string $pet_owner
 * @property string|null $pet_note
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
            [['pet_note'], 'string'],
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
            'pet_note' => 'Описание',
            'date_create' => 'Дата создания',
            'uploadFiles' => 'Изображения',
            'deleteFiles' => 'Отметьте изображения для удаления',
        ];
    }

    private function clearCahce()
    {
        Yii::$app->cache->delete('pets');
    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        $this->deleteFiles(null, true);
        $this->clearCahce();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if (!$insert) {
            $this->deleteFiles($this->deleteFiles);
        }
        $this->clearCahce();
    }

    public function getOwner()
    {
        return $this->hasOne(User::class, ['username' => 'pet_owner']);
    }



    /** UPLOAD FILES */

    protected function getPathFiles()
    {
        return str_replace('{id}', $this->id, '/public/upload/contest/pets/{id}/');
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
     * @return int
     */
    public function countLikes()
    {
        return (new Query())
            ->from('{{%contest_pets_like}}')
            ->where(['id_contest_pets' => $this->id])
            ->count();
    }

    /**
     * @return bool
     */
    public function isLike()
    {
        return (new Query())
            ->from('{{%contest_pets_like}}')
            ->where([
                'id_contest_pets' => $this->id,
                'username' => Yii::$app->user->identity->username,
            ])
            ->exists();
    }

    public function like($isLike)
    {
        if ($isLike) {
            Yii::$app->db->createCommand()
                ->delete('{{%contest_pets_like}}', [
                    'id_contest_pets' => $this->id,
                    'username' => Yii::$app->user->identity->username,
                ])
                ->execute();
        }
        else {
            Yii::$app->db->createCommand()
                ->insert('{{%contest_pets_like}}', [
                    'id_contest_pets' => $this->id,
                    'username' => Yii::$app->user->identity->username,
                    'date_crate' => new Expression('getdate()'),
                ])
                ->execute();
        }
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
