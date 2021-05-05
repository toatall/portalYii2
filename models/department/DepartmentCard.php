<?php

namespace app\models\department;

use app\components\Storage;
use Yii;

/**
 * This is the model class for table "{{%department_card}}".
 *
 * @property int $id
 * @property int $id_department
 * @property string|null $user_fio
 * @property string|null $user_rank
 * @property string|null $user_position
 * @property string|null $user_telephone
 * @property string|null $user_photo
 * @property int|null $user_level
 * @property int|null $sort_index
 * @property string|null $log_change
 * @property string|null $user_resp
 * @property string $date_create
 * @property string $date_edit
 *
 * @property Department $department
 */
class DepartmentCard extends \yii\db\ActiveRecord
{
    /**
     * Фотография сотрудника отдела
     * @var string
     */
    public $photoFile;

    /**
     * Удаление фотографии
     * @var boolean
     */
    public $deletePhotoFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%department_card}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_fio'], 'required'],
            [['id_department', 'user_level', 'sort_index'], 'integer'],
            [['log_change', 'user_resp'], 'string'],
            [['date_create', 'date_edit'], 'safe'],
            [['user_fio'], 'string', 'max' => 500],
            [['user_rank', 'user_position'], 'string', 'max' => 200],
            [['user_telephone'], 'string', 'max' => 50],
            [['user_photo'], 'string', 'max' => 250],
            [['id_department'], 'exist', 'skipOnError' => true, 'targetClass' => Department::class, 'targetAttribute' => ['id_department' => 'id']],
            [['photoFile'], 'file', 'skipOnEmpty' => true],
            [['deletePhotoFile'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'id_department' => 'Отдел',
            'user_fio' => 'ФИО',
            'user_rank' => 'Чин',
            'user_position' => 'Должность',
            'user_telephone' => 'Телефон',
            'user_photo' => 'Фотография',
            'user_level' => 'Уровень',
            'sort_index' => 'Индекс сортировки',
            'log_change' => 'Журнал изменений',
            'user_resp' => 'Обязанности',
            'date_create' => 'Индекс сортировки',
            'date_edit' => 'Дата изменения',
            'photoFile' => 'Фотография',
            'deletePhotoFile' => 'Удалить фотографию',
        ];
    }

    /**
     * Gets query for [[Department]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::class, ['id' => 'id_department']);
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @return bool
     * @throws \Exception
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // удаление файла
        if ($this->deletePhotoFile) {
            $this->deletePhotoFileFromDisk();
        }

        // если выбран файл, то загружаем
        if ($this->photoFile !== null && $this->photoFile instanceof \yii\web\UploadedFile) {
            if ($this->user_photo && !$this->isNewRecord) {
                $this->deletePhotoFileFromDisk();
            }

            // загрузка файла
            $file = $this->unloadPhotoFile();
            if ($file === false) {
                Yii::error('Ошибка загрузки файла сотрудника отдела. DepartmenCard::beforeSave(). ' . print_r(error_get_last(), true));
            }

            // сохранение имени в базе
            $this->user_photo = $file;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function afterDelete()
    {
        $this->deletePhotoFileFromDisk();
        parent::afterDelete();
    }

    /**
     * Максимальный размер (ширина) изображения фотографии сотрудника
     * @return integer
     */
    public function getParamMaxHeight()
    {
        return Yii::$app->params['department']['card']['maxHeightPhoto'];
    }

    /**
     * Каталог для загрузки файлов
     * @return mixed
     */
    protected function getParamUploadPath()
    {
        $path = Yii::$app->params['department']['card']['pathImage'];
        return str_replace('{code_no}', Yii::$app->userInfo->current_organization, $path);
    }

    /**
     * Загрузка файла
     * @throws \Exception
     * @return string|NULL
     */
    private function unloadPhotoFile()
    {
        $path = $this->getParamUploadPath();
        /* @var $storage Storage */
        $storage = \Yii::$app->storage;
        $file = $storage->saveUploadedFile($this->photoFile, $path, true);
        if ($file) {
            $this->resizeImage($file);
            return $storage->mergeUrl($path, basename($file));
        }
        return false;
    }

    /**
     * Изменить размер
     * @param string $image
     * @return boolean
     */
    private function resizeImage($image)
    {
        $height = $this->getParamMaxHeight();
        /* @var $storage Storage */
        $storage = \Yii::$app->storage;
        return $storage->resizeImage($image, 0, $height);
    }

    /**
     * Удаление старого файла
     */
    private function deletePhotoFileFromDisk()
    {
        // аккуратное удаление файла
        /* @var $storage \common\components\Storage */
        $storage = \Yii::$app->storage;
        if ($storage->deleteFile($this->user_photo)) {
            $this->user_photo = null;
        }
    }

    /**
     * Фотография сотрудника
     * @return string
     */
    public function getUserPhotoFile()
    {
        $path = Yii::getAlias('@webroot') . $this->user_photo;
        if (is_file($path) && file_exists($path)) {
            return $this->user_photo;
        }
        return '/img/user-default.png';
    }
}
