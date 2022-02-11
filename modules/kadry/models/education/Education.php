<?php

namespace app\modules\kadry\models\education;

use app\behaviors\AuthorBehavior;
use app\behaviors\ChangeLogBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\User;
use Yii;
use yii\helpers\FileHelper;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "{{%kadry_education}}".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $description_full
 * @property string|null $thumbnail
 * @property string|null $duration
 * @property string $author
 * @property string $date_create
 * @property string $date_update
 * @property string $log_change
 *
 * @property User $author0
 * @property EducationData[] $educationData
 * @property EducationUser $educationUser
 */
class Education extends \yii\db\ActiveRecord
{

    /**
     * Миниатюра
     * @var UploadedFile
     */
    public $uploadThumbnailImage;

    /**
     * Удаление миниатюры
     * @var boolean
     */
    public $deleteThumbnailImage;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%kadry_education}}';
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
            [['title', 'author'], 'required'],
            [['description', 'description_full', 'log_change'], 'string'],
            // [['date_create', 'date_update'], 'safe'],
            [['title', 'thumbnail'], 'string', 'max' => 500],
            [['duration'], 'string', 'max' => 100],
            [['author'], 'string', 'max' => 250],
            [['uploadThumbnailImage'], 'file', 'skipOnEmpty' => true],
            [['deleteThumbnailImage'], 'boolean'],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'title' => 'Заголовок',
            'description' => 'Описание (краткое)',
            'description_full' => 'Описание (полное)',
            'thumbnail' => 'Миниатюра',
            'duration' => 'Продолжительность',
            'author' => 'Автор',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'log_change' => 'Журнал изменений',
            'deleteThumbnailImage' => 'Удалить миниатюру',
        ];
    }

    /**
     * Gets query for [[Author0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorModel()
    {
        return $this->hasOne(User::class, ['username' => 'author']);
    }

    /**
     * Gets query for [[EducationDatas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducationData()
    {
        return $this->hasMany(EducationData::class, ['id_kadry_education' => 'id']);
    }

    /**
     * Gets query for [[EducationUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducationUser()
    {
        return $this->hasOne(EducationUser::class, ['id_kadry_education' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if (!$insert) {
            $this->touch('date_update');
        }
        $this->uploadThumbnail();
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        if ($this->thumbnail) {
            \Yii::$app->storage->deleteFile($this->thumbnail);
        }
    }

    /**
     * Загрузка миниатюры
     * @throws Exception
     */
    private function uploadThumbnail()
    {
        if ($this->uploadThumbnailImage && $this->uploadThumbnailImage instanceof \yii\web\UploadedFile) {
            // удаление старой миниатюры
            if (!$this->isNewRecord && $this->thumbnail != null) {
                // если имя такое же, то оставляем, просто заменим,
                // т.к. при удалении и загрузке нового изображения - удаляется новое (видимо операция удаления медленнее, чем загрузки)
                if (basename($this->thumbnail) != basename($this->uploadThumbnailImage->name)) {
                    \Yii::$app->storage->deleteFile($this->thumbnail);
                }
            }

            // загрузка новой миниатюры
            $path = $this->getPathThumb();
            $thumb = \Yii::$app->storage->saveUploadedFile($this->uploadThumbnailImage, $path, true);
            if ($thumb) {
                $this->thumbnail = $path . basename($thumb);
            }
        }
        // удаление миниатюры без сохранения новой
        else
        {
            if ($this->deleteThumbnailImage) {
                // удаление файла
                \Yii::$app->storage->deleteFile($this->thumbnail);
                // сохранение в БД
                $this->thumbnail = null;
            }
        }
    }

    /**
     * Каталог для загрузки миниатюр
     * @return string
     */
    protected function getPathThumb()
    {        
        if (!isset(\Yii::$app->params['modules']['kadry']['education']['uploadPath'])) {
            throw new ServerErrorHttpException('Не укзан каталог для выгрузки файлов. Параметр `modules.kadry.education.uploadPath`');
        }
        $path = \Yii::$app->params['modules']['kadry']['education']['uploadPath'];        
        if (!is_dir($path)) {
            FileHelper::createDirectory($path);
        }
        return $path;
    }

    /**
     * Миниатюра
     * Если файл не указан, то показывается файл, который укзан в параметрах (файл по умолчанию)
     * @return string
     */
    public function getThumbnailImage()
    {
        if (file_exists(Yii::getAlias('@webroot') . $this->thumbnail) && !empty($this->thumbnail)) {
            return $this->thumbnail;
        }
        return Yii::$app->params['modules']['kadry']['education']['thumbnailNotSet'] ?? null;        
    } 
    
    /**
     * Сохранение просмотра пользователем
     */
    public function saveVisit()
    {
        if ($this->educationUser) {
            return;
        }
        $educationUser = new EducationUser([
            'id_kadry_education' => $this->id,
            'username' => Yii::$app->user->identity->username,
        ]);
        $educationUser->save();
        $this->link('educationUser', $educationUser);
    }

}
