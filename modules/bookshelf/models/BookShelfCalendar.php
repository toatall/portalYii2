<?php

namespace app\modules\bookshelf\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\ChangeLogBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\User;
use Yii;
use yii\helpers\FileHelper;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "{{%book_shelf_calendar}}".
 *
 * @property int $id
 * @property string $date_birthday
 * @property string|null $date_die
 * @property string $writer
 * @property string|null $photo
 * @property string|null $description
 * @property string $author
 * @property string $date_create
 * @property string $date_update
 * @property string $log_change
 *
 * @property User $authorModel
 */
class BookShelfCalendar extends \yii\db\ActiveRecord
{
 
    /**
     * Фото писателя
     * @var UploadedFile
     */
    public $uploadPhoto;

    /**
     * Удаление фото писателя
     * @var boolean
     */
    public $deletePhoto;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book_shelf_calendar}}';
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
            [['date_birthday', 'writer'], 'required'],
            [['date_birthday', 'date_die', 'date_create', 'date_update'], 'safe'],
            [['description', 'log_change'], 'string'],
            [['writer', 'photo'], 'string', 'max' => 500],
            [['author'], 'string', 'max' => 250],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
            [['uploadPhoto'], 'file', 'skipOnEmpty' => true],
            [['deletePhoto'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'date_birthday' => 'Дата рождения',
            'date_die' => 'Дата смерти',
            'writer' => 'ФИО',
            'photo' => 'Фото',
            'description' => 'Описание',
            'author' => 'Автор',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'log_change' => 'Журнал изменений',
            'deletePhoto' => 'Удалить фотографию',
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
     * @return string
     */
    public function getPhoto()
    {
        $defaultImg = Yii::$app->params['modules']['bookshelf']['defaultImageWriter'] ?? null;
        if ($this->photo && file_exists(Yii::getAlias('@webroot') . $this->photo)) {
            return $this->photo;
        }
        return $defaultImg;
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
        $this->processUploadPhoto();
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        if ($this->photo) {
            \Yii::$app->storage->deleteFile($this->photo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        $this->date_birthday = $this->date_birthday != null ? Yii::$app->formatter->asDate($this->date_birthday) : null;
        $this->date_die = $this->date_die != null ? Yii::$app->formatter->asDate($this->date_die) : null;
    }
    
    /**
     * Загрузка фотографии
     * @throws Exception
     */
    private function processUploadPhoto()
    {
        if ($this->uploadPhoto && $this->uploadPhoto instanceof \yii\web\UploadedFile) {
            // удаление старой фотографии
            if (!$this->isNewRecord && $this->photo != null) {
                // если имя такое же, то оставляем, просто заменим,
                // т.к. при удалении и загрузке нового изображения - удаляется новое
                if (basename($this->photo) != basename($this->uploadPhoto->name)) {
                    \Yii::$app->storage->deleteFile($this->photo);
                }
            }

            // загрузка новой фотографии
            $path = $this->getUploadPath();
            $thumb = \Yii::$app->storage->saveUploadedFile($this->uploadPhoto, $path, true);
            if ($thumb) {
                $this->photo = $path . basename($thumb);
            }
        }
        // удаление фото без сохранения новой
        else
        {
            if ($this->deletePhoto) {
                \Yii::$app->storage->deleteFile($this->photo);               
                $this->photo = null;
            }
        }
    }

    /**
     * Каталог для загрузки миниатюр
     * @return string
     */
    protected function getUploadPath()
    {        
        if (!isset(\Yii::$app->params['modules']['bookshelf']['uploadPathWriter'])) {
            throw new ServerErrorHttpException('Не укзан каталог для выгрузки файлов. Параметр `modules.bookshelf.uploadPathWriter`');
        }
        $path = \Yii::$app->params['modules']['bookshelf']['uploadPathWriter'];        
        if (!is_dir($path)) {
            FileHelper::createDirectory($path);
        }
        return $path;
    }
    
}
