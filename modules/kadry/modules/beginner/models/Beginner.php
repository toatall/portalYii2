<?php

namespace app\modules\kadry\modules\beginner\models;

use app\behaviors\AuthorBehavior;
use yii\behaviors\TimestampBehavior;
use app\helpers\ImageHelper;
use app\helpers\StringHelper;
use Yii;
use app\models\department\Department;
use app\models\User;

/**
 * This is the model class for table "p_beginner".
 *
 * @property int $id
 * @property int $id_department
 * @property string $fio
 * @property string $date_employment
 * @property string|null $description
 * @property string $js
 * @property string $css
 * @property int $date_create
 * @property int $date_update
 * @property string|null $author
 *
 * @property User $authorModel
 * @property Department $departmentModel
 */
class Beginner extends \yii\db\ActiveRecord
{

    /**
     * Миниатюра
     * @var string
     */
    public $thumbUpload;

    /**
     * Изображения
     * @var array
     */
    public $filesUpload;    

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%beginner}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [            
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
            ],
            ['class' => AuthorBehavior::class],
            ['class' => \app\behaviors\FileUploadBehavior::class],
        ];
    }

    /**
     * Роль модератора
     * @return string
     */
    public static function getRoleModerator()
    {
        return Yii::$app->controller->module->params['roles']['moderator'];
    }

    /**
     * Проверка наличия прав модератора
     * @return boolean
     */
    public static function isRoleModerator()
    {
        if (Yii::$app->user->can('admin') || Yii::$app->user->can(self::getRoleModerator())) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_department', 'fio'], 'required'],
            [['id_department'], 'integer'],
            [['description', 'js', 'css'], 'string'],
            [['fio'], 'string', 'max' => 500],            
            [['date_employment'], 'date'],
            [['id_department'], 'exist', 'skipOnError' => true, 'targetClass' => Department::class, 'targetAttribute' => ['id_department' => 'id']],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
            [['filesUpload'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 30],
            [['thumbUpload'], 'file', 'skipOnEmpty' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'id_department' => 'Отдел',
            'fio' => 'ФИО',
            'date_employment' => 'Дата приема',
            'description' => 'Описание',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'author' => 'Автор',
            'thumbUpload' => 'Миниатюра',
            'filesUpload' => 'Фотографии',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->date_employment = $this->date_employment ? Yii::$app->formatter->asDate($this->date_employment) : null;
    }
    
    public function afterDelete() 
    {
        parent::afterDelete();
        $this->clearDir($this->getThumbPath());
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorModel()
    {
        return $this->hasOne(User::class, ['username' => 'author']);
    }

    /**
     * Gets query for [[Department]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartmentModel()
    {
        return $this->hasOne(Department::class, ['id' => 'id_department']);
    }

    /**
     * Каталог с изображением-миниатюрой
     * @return stirng
     */
    public function getThumbPath()
    {
        $path = Yii::$app->controller->module->params['path']['thumbnail'];
        return StringHelper::manyReplace($path, [
            '{code}' => '8600',
            '{id}' => $this->id,
        ]);
    }
    
    /**
     * Загрузка миниатюры
     */
    public function uploadThumn()
    {        
        $path = $this->getThumbPath();        
        $this->uploadPath = $path;
        $this->uploadFile('thumbUpload', function() use ($path) { $this->clearDir($path); });
    }
    
    /**
     * Загрузка файлов
     */
    public function uploadFilesGallery()
    {
        $path = $this->getGalleryPath();
        $this->uploadPath = $path;
        $this->uploadFiles('filesUpload');
    }
    

    /**
     * Каталог с галлереей
     * @return string
     */
    public function getGalleryPath()
    {
        $path = Yii::$app->controller->module->params['path']['gallery'];
        return StringHelper::manyReplace($path, [
            '{code}' => '8600',
            '{id}' => $this->id,
        ]);
    }

    /**
     * Миниатюра
     * @return string
     */
    public function getThumbImage()
    {
        $path = $this->getThumbPath();
        $fullPath = Yii::getAlias('@webroot') . $path;        
        if (!file_exists($fullPath) || dir($fullPath) === false) {
            return null;
        }
        $thumbImage = ImageHelper::findImages($fullPath)[0] ?? null;
        if ($thumbImage === null) {
            return null;
        }
        return $path . basename($thumbImage);
    }
    

    /**
     * Галлерея
     * @return array
     */
    public function getGalleryImages()
    {
        $path = $this->getGalleryPath();
        $fullPath = Yii::getAlias('@webroot') . $path;
        if (!file_exists($fullPath) || dir($fullPath) === false) {
            return [];
        }
        return array_map(function($value) use ($path) {           
            return $path . basename($value);
            
        }, ImageHelper::findImages($fullPath));
    }
    
    /**
     * Поиск миниатюры
     * @param string $image
     * @return string
     */
    protected function findThumbImage($image)
    {
        $thumbImage = str_replace(basename($image), Yii::$app->storage->mergeUrl('_thumb', basename($image)), $image);
        if (file_exists(Yii::getAlias('@webroot') . $thumbImage)) {
            return $thumbImage;
        }
        return $image;
    }
    
    


}
