<?php

namespace app\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use app\helpers\UploadHelper;
use Yii;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%protocol}}".
 *
 * @property int $id
 * @property string $type_protocol
 * @property string $date
 * @property string $number
 * @property string $name
 * @property string|null $executor
 * @property string|null $execute_description
 * @property string $date_create
 * @property string $date_update
 * @property string $author
 *
 * @property User $authorModel
 */
class Protocol extends \yii\db\ActiveRecord
{

    /**
     * Файлы протокола
     * @var UploadedFile[]
     */
    public $uploadMainFiles;

    /**
     * Файлы по исполнению протокола
     * @var UploadedFile[]
     */
    public $uploadExecuteFiles;

    /**
     * Файлы протокола отмеченные для удаления
     * @var string[]
     */
    public $deleteMainFiles;

    /**
     * Файлы по исполнению отмеченные для удаления
     * @var string[]
     */
    public $deleteExecuteFiles;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%protocol}}';
    }

    /**
     * @return string
     */
    public static function roleModerator() 
    {
        return Yii::$app->params['protocol']['roles']['moderator'];
    }

    /**
     * @return boolean
     */
    public static function isRoleModerator()
    {
        return Yii::$app->user->can('admin') || Yii::$app->user->can(self::roleModerator());
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
    public function rules()
    {
        return [
            [['date', 'number', 'name', 'type_protocol'], 'required'],
            [['date', 'date_create', 'date_update'], 'safe'],
            [['execute_description'], 'string'],
            [['number'], 'string', 'max' => 150],
            [['name', 'executor'], 'string', 'max' => 2500],
            [['author'], 'string', 'max' => 250],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
            [['uploadMainFiles', 'uploadExecuteFiles'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 30],
            [['deleteMainFiles', 'deleteExecuteFiles'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     * {@codeCoverageIgnore}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'date' => 'Дата протокола',
            'number' => 'Номер протокола',
            'name' => 'Наименование протокола',
            'executor' => 'Отвественные испонители',
            'execute_description' => 'Исполнение',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'author' => 'Автор',
            'uploadMainFiles' => 'Файлы протокола',
            'uploadExecuteFiles' => 'Файлы исполнения протокола',
            'deleteMainFiles' => 'Отметьте файлы для удаления',
            'deleteExecuteFiles' => 'Отметьте файлы для удаления',
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
     * Загрузка всех файлов
     * @see ProtocolController.actionCreate()
     * @see ProtocolController.actionUpdate()
     */
    public function uploadFiles()
    {   
        (new UploadHelper($this->getPathMainFiles()))
            ->uploadFiles($this->uploadMainFiles);

        (new UploadHelper($this->getPathExecuteFiles()))
            ->uploadFiles($this->uploadExecuteFiles);
    }

    /**
     * каталог загрузки файлов протокола
     * @return string 
     */
    private function getPathMainFiles()
    {
        return str_replace('{id}', $this->id, Yii::$app->params['protocol']['path']['files_main']);
    }

    /**
     * каталог загрузки файлов исполнения протокола
     * @return string 
     */
    private function getPathExecuteFiles()
    {
        return str_replace('{id}', $this->id, Yii::$app->params['protocol']['path']['files_execute']);
    }

    /**
     * Файлы протокола
     * @return string[]
     */
    public function getFilesMain()
    {
        return $this->prepareFiles(str_replace('{id}', $this->id, $this->getPathMainFiles()));
    }

    /**
     * Файлы исполнения протокола
     * @return string[]
     */
    public function getFilesExecute()
    {
        return $this->prepareFiles(str_replace('{id}', $this->id, $this->getPathExecuteFiles()));
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
    }

    /**
     * Подготовка имен файлов
     * @param string
     * @return string[]
     */
    private function prepareFiles($path)
    {        
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
     * имена файлов протоколов для удаления
     * если параметр $files не передается, то удаляются все файлы
     * @param string[] $files
     */
    public function deleteMainFiles($files = null)
    {
        $allFiles = $this->getFilesMain();
        $this->deleteFiles($files, $allFiles);                
    }  

    /**
     * имена файлов исполнения протоколов для удаления
     * если параметр $files не передается, то удаляются все файлы
     * @param string[] $files
     */
    public function deleteExecuteFiles($files = null)
    {        
        $allFiles = $this->getFilesExecute();
        $this->deleteFiles($files, $allFiles);   
    }

    /**
     * @param string[]|null $files файлы отмеченны для удаления
     * @param string[] $allFiles все файлы
     */
    private function deleteFiles($files, $allFiles) 
    {               
        if (!$files) {
            return;
        }
        
        foreach ($allFiles as $file) {
            if (in_array(basename($file), $files)) {
                FileHelper::unlink(Yii::getAlias('@webroot') . $file);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (!$insert) {
            $this->deleteMainFiles($this->deleteMainFiles);
            $this->deleteExecuteFiles($this->deleteExecuteFiles);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        if ($this->date) {
            $this->date = Yii::$app->formatter->asDate($this->date);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        $this->deleteMainFiles($this->getFilesMain());
        $this->deleteExecuteFiles($this->getFilesExecute());
        $pathMain = Yii::getAlias('@webroot') . $this->getPathMainFiles();
        $pathExecute = Yii::getAlias('@webroot') . $this->getPathExecuteFiles();
        if (is_dir($pathMain)) {
            FileHelper::removeDirectory($pathMain);
        }
        if (is_dir($pathExecute)) {
            FileHelper::removeDirectory($pathExecute);
        }
    }


}
