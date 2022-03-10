<?php

namespace app\modules\bookshelf\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\ChangeLogBehavior;
use app\behaviors\DatetimeBehavior;
use app\helpers\DateHelper;
use app\models\User;
use Yii;
use yii\helpers\FileHelper;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%book_shelf}}".
 *
 * @property int $id
 * @property string|null $writer
 * @property string $title
 * @property float|null $rating
 * @property string $place
 * @property string|null $photo
 * @property string|null $description
 * @property string|null $date_received
 * @property string|null $date_away
 * @property int $book_status
 * @property string $author
 * @property string $date_create
 * @property string $date_update
 * @property string $log_change
 *
 * @property User $author0
 * @property BookShelfPlace[] $bookShelfPlaces
 * @property BookShelfRating[] $bookShelfRatings
 */
class BookShelf extends \yii\db\ActiveRecord
{

    /**
     * Миниатюра
     * @var UploadedFile
     */
    public $uploadPhoto;

    /**
     * Удаление миниатюры
     * @var boolean
     */
    public $deletePhoto;


    const STATUS_UNKNOWN = 0;
    const STATUS_IN_STOCK = 1;
    const STATUS_AWAY = 2;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book_shelf}}';
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
     * Роль администратора 
     * @return string
     */
    public static function roleAdmin()
    {
        $role = Yii::$app->params[''] ?? null;
        if ($role == null) {
            $role = 'bookshelf.admin';
        }
        return $role;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['writer', 'title', 'place', 'book_status', 'date_received'], 'required'],
            [['rating'], 'number'],
            [['description', 'log_change'], 'string'],
            [['date_received', 'date_away', 'date_create', 'date_update'], 'safe'],
            [['book_status'], 'integer'],
            [['writer', 'photo'], 'string', 'max' => 500],
            [['title'], 'string', 'max' => 1000],
            [['place'], 'string', 'max' => 100],
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
            'writer' => 'Писатель',
            'title' => 'Заголовок',
            'rating' => 'Рейтинг',
            'place' => 'Место размещения',
            'photo' => 'Фото',
            'description' => 'Описание',
            'date_received' => 'Дата получения',
            'date_away' => 'Дата отдачи',
            'book_status' => 'Статус',
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
    public function getAuthor0()
    {
        return $this->hasOne(User::class, ['username' => 'author']);
    }

    /**
     * Gets query for [[BookShelfPlaces]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookShelfPlaces()
    {
        return $this->hasMany(BookShelfPlace::class, ['id_book_shelf' => 'id']);
    }

    /**
     * Gets query for [[BookShelfRatings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookShelfRatings()
    {
        return $this->hasMany(BookShelfRating::class, ['id_book_shelf' => 'id']);
    }

    /**
     * Статусы
     * @return array
     */
    public function getStatuses()
    {
        return [
            self::STATUS_IN_STOCK => 'В наличии',
            self::STATUS_AWAY => 'Нет в наличии',
        ];
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        $defaultImg = Yii::$app->params['modules']['bookshelf']['defaultImageBook'] ?? null;
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
        $this->date_received = $this->date_received != null ? Yii::$app->formatter->asDate($this->date_received) : null;
        $this->date_away = $this->date_away != null ? Yii::$app->formatter->asDate($this->date_away) : null;
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
        if (!isset(\Yii::$app->params['modules']['bookshelf']['uploadPathBook'])) {
            throw new ServerErrorHttpException('Не укзан каталог для выгрузки файлов. Параметр `modules.bookshelf.uploadPathBook`');
        }
        $path = \Yii::$app->params['modules']['bookshelf']['uploadPathBook'];        
        if (!is_dir($path)) {
            FileHelper::createDirectory($path);
        }
        return $path;
    }

    /**
     * Обновление рейтинга
     */
    public function updateRating()
    {
        Yii::$app->db->createCommand("
            update {{%book_shelf}}
            set rating = (
                select avg(book_shelf_rating.rating) 
                from {{%book_shelf_rating}} book_shelf_rating 
                where book_shelf_rating.id_book_shelf = :id_book_shelf
            )
            where id = :id
        ", [
            ':id' => $this->id,
            ':id_book_shelf' => $this->id,
        ])->execute();
    }

    /**
     * Является ли книга новой
     * (новая - 31 день)
     * @return boolean
     */
    public function isNewBook()
    {
        if (!$this->date_received) {
            return false;
        }
        return DateHelper::dateDiffDays($this->date_received) <= 31;
    }

    /**
     * Права на редактирование
     */
    public function isEditor()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        if (Yii::$app->user->can('admin')) {
            return true;
        }
        if (Yii::$app->user->can(self::roleAdmin())) {
            return true;
        }
        return false;
    }


}
