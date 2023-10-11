<?php

namespace app\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use app\behaviors\FileUploadBehavior;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%automation_routine}}".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $owners
 * @property string $region_mail
 * @property string|null $ftp_path
 * @property string $author
 * @property string $date_create
 * @property string|null $date_update
 * 
 * @property string $uploadPath
 *
 */
class AutomationRoutine extends \yii\db\ActiveRecord
{

    /**
     * Файлы ПМ
     * @var string[]
     */
    public $uploadFiles;

    /**
     * Имена файлов для удаления
     * @var mixed[]
     */
    public $deleteFiles;

    /**
     * @var mixed
     */
    public $uploadInstruction;

    /**
     * @var bool
     */
    public $deleteInstruction;

    /** 
     * Переопределение из FileUploadBehavior
     */
    public $makeThumbs = false;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%automation_routine}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'owners'], 'required'],
            [['description'], 'string'],
            [['date_create', 'date_update'], 'safe'],
            [['title', 'region_mail'], 'string', 'max' => 300],
            [['ftp_path', 'author'], 'string', 'max' => 250],
            [['uploadFiles'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 30],            
            [['uploadInstruction'], 'file', 'skipOnEmpty' => true],
            [['deleteFiles', 'deleteInstruction'], 'safe'],
        ];
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
            ['class' => FileUploadBehavior::class],
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
            'description' => 'Описание',           
            'ftp_path' => 'Ссылка на ftp',
            'region_mail' => 'Реквизиты письма Управления',
            'owners' => 'Владелец (структурное подразделение)',
            'author' => 'Автор',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'uploadFiles' => 'Файлы ПМ',
            'uploadInstruction' => 'Инструкция',
            'deleteFiles' => 'Удалить файлы',
            'deleteInstruction' => 'Удалить инструкцию',
        ];
    }

    /**
     * Сохранение информации о скачанном файле
     * @return int
     */
    public function saveDownload($filename)
    {
        return Yii::$app->db->createCommand()
            ->insert('{{%automation_routine_downloads}}', [
                'id_automation_routine' => $this->id,
                'filename' => $filename,
                'author' => Yii::$app->user->identity->username,
                'date_create' => new Expression('getdate()'),
            ])
            ->execute();
    }

    /**
     * Базовый каталог
     * @return string
     */
    private function getBasePath($id)
    {
        return "/public/upload/portal/automation-routine/{$id}/";
    }

    /**
     * Каталог с файлами
     * @return string
     */
    private function getFilesPath()
    {
        return $this->getBasePath($this->id) . "files/";
    }

    /**
     * Каталог с инструкцией
     * @return string
     */
    private function getInstructionPath()
    {
        return $this->getBasePath($this->id);
    }

    /**
     * Поиск файлов
     * @return string[]|null
     */
    private function searchFiles($path)
    {
        if (is_dir($path)) {
            return FileHelper::findFiles($path, ['recursive' => false]);
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
     * @return string[]
     */
    public function getFiles()
    {
        return $this->prepareFiles($this->getFilesPath());
    }

    /**
     * @return string|null
     */
    public function getInstruction()
    {
        return $this->prepareFiles($this->getInstructionPath())[0] ?? null;
    }

    /**
     * Загрузка файлов
     */
    protected function uploadingFiles(): void
    {
        // если есть что удалять, то сначала удаляем
        if ($this->deleteFiles) {
            $this->deleteFiles($this->deleteFiles);           
        }

        $this->uploadPath = $this->getFilesPath();
        $this->uploadFiles('uploadFiles');
    }

    /**
     * Загрузка инструкции
     */
    protected function uploadingInstruction()
    {        
        $instrunction = $this->getInstruction();
        if ($this->deleteInstruction) {
            if ($instrunction) { 
                @FileHelper::unlink(Yii::getAlias('@webroot') . $instrunction); 
            }
        }

        $this->uploadPath = $this->getInstructionPath();
        $this->uploadFiles('uploadInstruction', function() use ($instrunction) { 
            if ($instrunction) { 
                @FileHelper::unlink(Yii::getAlias('@webroot') . $instrunction); 
            } 
        });
    }

    /**
     * @param string[]|null $files файлы отмеченны для удаления  
     */
    private function deleteFiles($files) 
    {               
        if (!$files) {
            return;
        }
        $files = array_filter((array)$files);
        
        foreach ($files as $file) {           
            FileHelper::unlink(Yii::getAlias('@webroot') . $file);            
        }
    }

    /**
     * Загрузка всех файлов
     */
    protected function uploadAll()
    {   
        $this->uploadingFiles();
        $this->uploadingInstruction();
    }

    /**
     * {@inheritDoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->uploadAll();
    }

    /**
     * {@inheritDoc}
     */
    public function afterDelete()
    {
        parent::afterDelete();
        $this->deleteFiles($this->getFiles());
        $this->deleteFiles($this->getInstruction());
    }


    public function getRateStatictic()
    {
        return (new Query())
            ->select('COUNT([[rate]]) AS count_rate, AVG(CAST([[rate]] AS FLOAT)) AS avg_rate')
            ->from('{{%automation_routine_rate}}')
            ->where([
                'id_automation_routine' => $this->id,                
            ])
            ->one();
    }

    public function getRate()
    {
        return (new Query())
            ->from('{{%automation_routine_rate}}')
            ->where([
                'id_automation_routine' => $this->id,
                'author' => Yii::$app->user->identity->username,
            ])
            ->one()['rate'] ?? null;
    }

    public function updateRate($rate)
    {
        $exists = ($this->getRate() != null);

        if (is_numeric($rate)) {
            if ($exists) {
                $this->deleteRate();                
            }
            $this->insertRate($rate);
        }
        else {
            $this->deleteRate();
        }
    }

    protected function insertRate($rate)
    {
        Yii::$app->db->createCommand()
            ->insert('{{%automation_routine_rate}}', [
                'id_automation_routine' => $this->id,
                'rate' => $rate,
                'author' => Yii::$app->user->identity->username,
                'date_create' => new Expression('getdate()'),
            ])
            ->execute();
    }

    protected function deleteRate()
    {
        Yii::$app->db->createCommand()
            ->delete('{{%automation_routine_rate}}', [
                'id_automation_routine' => $this->id,
                'author' => Yii::$app->user->identity->username,                
            ])
            ->execute();
    }
   

}
