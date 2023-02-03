<?php

namespace app\models\lifehack;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use app\helpers\UploadHelper;
use app\models\Organization;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%lifehack}}".
 *
 * @property int $id
 * @property string $org_code
 * @property string $tags
 * @property string $title
 * @property string|null $text
 * @property string|null $author_name
 * @property string|null $date_create
 * @property string|null $date_update
 * @property string|null $username
 * @property string|null $log_change
 * @property array $tagsArray
 * 
 * @property float $avg
 *
 * @property LifehackFile[] $lifehackFiles
 * @property LifehackLike $lifehackLike
 * @property Organization $organizationModel
 */
class Lifehack extends \yii\db\ActiveRecord
{
    /**
     * Файлы
     * @var UploadedFile[]
     */
    public $uploadFiles;

    /**
     * Файлы отмеченные для удаления
     * @var int[]
     */
    public $deleteFiles;
    
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
            [
                'class' => AuthorBehavior::class,
                'author_at' => 'usernname',
            ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lifehack}}';
    }    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['org_code', 'tagsArray', 'title'], 'required'],
            [['text', 'log_change'], 'string'],
            [['date_create', 'date_update', 'tagsArray'], 'safe'],
            [['org_code'], 'string', 'max' => 5],
            [['tags', 'title'], 'string', 'max' => 2000],
            [['author_name'], 'string', 'max' => 500],
            [['username'], 'string', 'max' => 250],
            [['uploadFiles'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 30],
            [['deleteFiles', 'deleteImages'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'org_code' => 'Организация',
            'tags' => 'Тэги',
            'tagsArray' => 'Тэги',
            'title' => 'Заголовок',
            'text' => 'Текст',
            'author_name' => 'Автор лайфхака',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'username' => 'Автор',
            'log_change' => 'Журнал изменений',
            'uploadFiles' => 'Файлы',
            'deleteFiles' => 'Отметьте файлы для удаления',
        ];
    }
    
    /**
     * @return array
     */
    public function getTagsArray()
    {        
        if (trim($this->tags) != '') {
            return explode('/', $this->tags);
        }
        return [];
    }

    /**
     * @param array $value
     */
    public function setTagsArray($value)
    {
        if (is_array($value) && !empty($value)) {
            $this->tags = implode('/', $value);
        }
        else {
            $this->tags = null;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizationModel()
    {
        return $this->hasOne(Organization::class, ['code' => 'org_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLifehackLike()
    {
        return $this->hasOne(LifehackLike::class, ['id_lifehack' => 'id'])
            ->where([
                'username' => Yii::$app->user->identity->username ?? null,
            ]);
    }


    /**
     * Файлы, прикрепленные к новости
     * @param $idFiles int[]
     * @return \yii\db\ActiveQuery
     */
    public function getLifehackFiles($idFiles = [])
    {
        $relation = $this->hasMany(LifehackFile::class, ['id_lifehack' => 'id']);            
        if (is_array($idFiles) && count($idFiles) > 0) {
            $relation->andWhere(['in', 'id', $idFiles]);
        }
        return $relation;
    }

    /**
     * Удаление файлов
     * @param int[] $idFiles    
     */
    protected function deleteUploadFiles($idFiles = [])
    {
        // получение идентификаторов файлов
        $files = $this->getLifehackFiles($idFiles)->all();

        // удаление файлов
        foreach ($files as $file) {
            $file->delete();
        }
    }

    /**
     * Загрузка файлов
     */
    private function uploadFiles()
    {
        if (is_array($this->uploadFiles) && count($this->uploadFiles) > 0) {
            $path = $this->getPathFiles();
            $path = str_replace('{id}', $this->id ?? '0', $path);         
            $helper = new UploadHelper($path);
            $helper->uploadFiles($this->uploadFiles, function ($file, $saveFile, $path) {
                // сохранение в БД
                $fileModel = new LifehackFile([
                    'id_lifehack' => $this->id,
                ]);
                $fileModel->filename = \Yii::$app->storage->addEndSlash($path) . $file->name;              
                if ($fileModel->save()) {

                }
            });
        }
    }

    /**
     * @return float
     */
    public function getAvg()
    {
        return (new Query())
            ->from('{{%lifehack_like}}')
            ->where(['id_lifehack' => $this->id])
            ->average('cast(rate as float)');        
    }

    /**
     * @return int
     */
    public function getCountRate()
    {
        return  (new Query())
        ->from('{{%lifehack_like}}')
        ->where(['id_lifehack' => $this->id])
        ->count('id');
    }

    /**
     * Каталог для загрузки файлов
     * @return string
     */
    protected function getPathFiles()
    {
        return \Yii::$app->params['lefehack']['path']['files'];
    }
    
    /**
     * @return array
     */
    public function getUploadedFiles()
    {
        return ArrayHelper::map($this->getLifehackFiles()->all(), 'id', 'filename');
    }

    /**
     * Является ли пользователь редактором
     */
    public static function isEditor()
    {
        return Yii::$app->user->can('admin') || Yii::$app->user->can('lifehack-editor');
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        // удаление выбранных файлов
        if (is_array($this->deleteFiles) && count($this->deleteFiles) > 0) {
            $this->deleteUploadFiles($this->deleteFiles);
        }

        // Загрузка файлов
        $this->uploadFiles();
    }

    /**
     * Есть лайк от этого пользователя
     * @return bool
     */
    public function liked()
    {
        return (new Query())
            ->from('{{%lifehack_like}}')
            ->where(['id_lifehack' => $this->id])
            ->exists();
    }
    
}
