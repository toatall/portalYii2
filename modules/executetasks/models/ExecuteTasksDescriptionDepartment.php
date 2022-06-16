<?php

namespace app\modules\executetasks\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use app\helpers\UploadHelper;
use app\models\department\Department;
use Yii;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%execute_tasks_description_department}}".
 *
 * @property int $id
 * @property int $id_department
 * @property string $fio
 * @property string|null $telephone
 * @property string|null $post
 * @property string|null $rank
 * @property string|null $description
 * @property string $date_create
 * @property string $date_update
 * @property string $author
 *
 * @property Department $department
 */
class ExecuteTasksDescriptionDepartment extends \yii\db\ActiveRecord
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
        return '{{%execute_tasks_description_department}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_department', 'fio'], 'required'],
            [['id_department'], 'integer'],
            [['description'], 'string'],
            [['date_create', 'date_update'], 'safe'],
            [['fio', 'post', 'rank'], 'string', 'max' => 500],
            [['telephone'], 'string', 'max' => 200],
            [['author'], 'string', 'max' => 250],
            [['id_department'], 'unique'],
            [['id_department'], 'exist', 'skipOnError' => true, 'targetClass' => Department::class, 'targetAttribute' => ['id_department' => 'id']],
            [['uploadImage'], 'file', 'skipOnEmpty' => true],
            [['deleteImage'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
            'telephone' => 'Телефон',
            'post' => 'Должность',
            'rank' => 'Чин',
            'description' => 'Описание',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'author' => 'Автор',
            'deleteImage' => 'Удалить изображение',
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
     * @return string
     */
    private function getPathUploadFile()
    {
        return str_replace('{id}', $this->id, Yii::$app->params['modules']['executeTasks']['department']['path']);
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
        return $this->prepareFile(str_replace('{id}', $this->id, $this->getPathUploadFile()));
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
            if ($this->deleteImage) {
                $this->deleteImageFile($this->getImage());
            }
        }
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


}
