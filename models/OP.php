<?php

namespace app\models;

use app\components\Storage;
use app\helpers\UploadHelper;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\HttpException;

/**
 * This is the model class for table "{{%op_files}}".
 *
 * @property int $id
 * @property int $id_op_group
 * @property int $type_section
 * @property string $name
 * @property string|null $file_name
 * @property string $date_create
 *
 */
class OP extends \yii\db\ActiveRecord
{

    /**
     * Поле в форме для фйла
     * @var string
     */
    public $file;

    /**
     * Флажок удаления файла (только в actionOpUpdate())
     * @var boolean
     */
    public $deleteUploadedFile;

    /**
     * Методические рекомендации, инструкции, протоколы рабочих совещаний
     */
    const SECTION_DOCUMENTS = 0;
    const SECTION_DOCUMENTS_TITLE = 'Методические рекомендации, инструкции, протоколы рабочих совещаний';

    /**
     * Арбитражная практика
     */
    const SECTION_ARBITRATION = 1;
    const SECTION_ARBITRATION_TITLE = 'Арбитражная практика';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%op_files}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_op_group', 'type_section', 'name'], 'required'],
            [['id_op_group', 'type_section'], 'integer'],
            [['date_create'], 'safe'],
            [['name', 'file_name'], 'string', 'max' => 1000],
            [['file'], 'file', 'skipOnEmpty' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_op_group' => 'Id Op Group',
            'type_section' => 'Type Section',
            'name' => 'Текст',
            'file_name' => 'File Name',
            'date_create' => 'Date Create',
            'file' => 'Файл',
        ];
    }


    /**
     * @return array
     */
    protected function getSections()
    {
        $query = (new Query())
            ->from('{{%op_group}}')
            ->all();
        return ArrayHelper::map($query, 'id', 'name');
    }

    /**
     * @return array
     */
    public function getData()
    {
        $sections = $this->getSections();
        $result = [];
        foreach ($sections as $id => $section) {
            $result[$id]['data'] = $this->getDataBySection($id);
            $result[$id]['title'] = $section;
        }
        return $result;
    }

    /**
     * @param $idSection
     * @return array
     */
    protected function getDataBySection($idSection)
    {
        return (new Query())
            ->from('{{%op_files}}')
            ->where(['id_op_group'=>$idSection])
            ->orderBy('id asc')
            ->all();
    }

    /**
     * Права редактроа?
     * @return bool
     * @throws HttpException
     */
    public function isEditor()
    {
        if (\Yii::$app->user->isGuest) {
            return false;
        }

        // если администратор
        if (\Yii::$app->user->can('admin')) {
            return true;
        }

        if (!isset(\Yii::$app->params['department']['OP']['editors'])) {
            throw new HttpException('Не определен параметр department.OP.editors');
        }
        $editors = Yii::$app->params['department']['OP']['editors'];
        if (!is_array($editors)) {
            $editors[] = $editors;
        }

        // если есть учетка
        if (in_array(Yii::$app->user->identity->username, $editors)) {
            return true;
        }

        // если есть группа @todo
        /*
        foreach (UserInfo::inst()->ADMemberOf as $member) {
            if (in_array($member, $editors)) {
                return true;
            }
        }
        */

        return false;
    }

    /**
     * Загрузка файла
     * @throws \Exception
     * @return string|NULL
     */
    private function unloadFile()
    {
        if ($this->file && $this->file instanceof \yii\web\UploadedFile) {
            $path = $this->getParamUploadPath();
            $file = \Yii::$app->storage->saveUploadedFile($this->file, $path);
            if ($file) {
                $this->file_name = \Yii::$app->storage->addEndSlash($path) . $this->file->name;
                $this->save(false, ['file_name']);
            }
        }
    }

    /**
     * @return string|string[]
     */
    private function getParamUploadPath()
    {
        $path = Yii::$app->params['department']['OP']['path'];
        return str_replace('{id}', $this->id, $path);
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->unloadFile();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Delete file
     * @throws \yii\base\ErrorException
     */
    public function afterDelete()
    {
        // удаление файлов
        $this->deleteUploadFile();

        // удаление каталога
        $this->deleteFolder();

        parent::afterDelete();
    }

    /**
     * @return mixed
     */
    private function deleteUploadFile()
    {
        if ($this->file_name != null) {
            return \Yii::$app->storage->deleteFile($this->file_name);
        }
    }

    /**
     * @throws \yii\base\ErrorException
     */
    private function deleteFolder()
    {
        $path = Yii::getAlias('@webroot') . $this->getParamUploadPath();
        FileHelper::removeDirectory($path);
    }

}
