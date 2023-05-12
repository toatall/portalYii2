<?php

namespace app\models;

use app\helpers\UploadHelper;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%organization}}".
 *
 * @property string $code
 * @property string $code_parent
 * @property string $name
 * @property int|null $sort
 * @property string $date_create
 * @property string $date_edit
 * @property string $name_short
 * @property string $date_end
 * @property string $description
 *
 * @property Department[] $departments
 * @property File[] $files
 * @property Group[] $groups
 * @property News[] $news
 * @property Telephone[] $telephones
 * @property Tree[] $trees
 * @property UserOrganization[] $userOrganizations
 * @property string $fullName
 */
class Organization extends \yii\db\ActiveRecord
{

    /**
     * Изображения для исторической справки
     * @var UploadedFile[]
     */
    public $uploadImages;


    /**
     * Изображения отмеченные для удаления
     * @var string[]
     */
    public $deleteImages;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%organization}}';
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function rules()
    {
        return [
            [['code', 'name', 'sort'], 'required'],
            [['sort'], 'integer'],
            [['date_create', 'date_edit', 'date_end', 'description'], 'safe'],
            [['code', 'code_parent'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 250],
            [['name_short'], 'string', 'max' => 200],
            [['code'], 'unique'],
            [['description'], 'required', 'on'=>'update-history-reference'],
            [['uploadImages'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 30],
            [['deleteImages'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Код',
            'name' => 'Наименование',
            'name_short' => 'Краткое наименование',
            'sort' => 'Сортировка',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата изменения',
            'date_end' => 'Дата окончания',
            'description' => 'Описание',
            'uploadImages' => 'Изображения',
            'deleteImages' => 'Отментьте изображения для удаления',
        ];
    }        

    /**
     * Актуальный список организаций (без реорганизованных)
     * @return yii\db\Query
     */
    public static function findActual()
    {
        return self::find()->andWhere(['code_parent'=>null]);
    }
    
    /**
     * Список организаций для списков
     * @return array
     */
    public static function getDropDownList($onlyIfns = false, $withNull = false, $onlyActual = true)
    {
        $query = self::find();
        if ($onlyIfns) {
            $query->where(['<>', 'code', '8600']);
        }
        if ($onlyActual) {
            $query->andWhere(['code_parent'=>null]);
        }
        $result = \yii\helpers\ArrayHelper::map($query->all(), 'code', 'fullName');
        if ($withNull) {
            $result = ArrayHelper::merge([''=>'Все'], $result);
        }
        return $result;
    }
    
    
    /**
     * Наименование организации с кодом
     * @return string
     */
    public function getFullName()
    {
        return $this->name . ' (' . $this->code . ')';
    }

    // /**
    //  * Получение текущей орагнизации (User.current_organization) для текущего пользователя
    //  * Если у пользователя по какой-либо причине отсутсвует доступ
    //  * к текущей организации, то выбирается первая доступная пользователю организации
    //  * @author toatall
    //  * @see User
    //  */
    // public static function loadCurrentOrganization()
    // {
    //     // если уже присвоен код организации, то выходим
    //     if (isset(\Yii::$app->userInfo->current_organization)) return;

    //     // выполняем поиск текущего пользователя
    //     $userModel = Yii::$app->user->identity; //User::model()->findByPk(Yii::app()->user->id);

    //     $userCurrentOrganization = isset($userModel->current_organization) && !empty($userModel->current_organization)
    //         ? $userModel->current_organization : null;

    //     // проверка прав у пользователя к текущей организации
    //     if (!User::checkRightOrganization($userCurrentOrganization))
    //     {
    //         $userCurrentOrganization = null;
    //     }
    //     else
    //     {
    //         Yii::$app->userInfo->current_organization = $userCurrentOrganization;
    //     }

    //     // если нет доступа к текущей организации
    //     if ($userCurrentOrganization===null)
    //     {
    //         $organizations = $userModel->organizations;
    //         if ($organizations != null)
    //         {
    //             if (isset($userModel->organization[0]->code))
    //             {
    //                 $userCurrentOrganization = $userModel->organization[0]->code;
    //                 Yii::$app->user->identity->changeOrganization($userCurrentOrganization);
    //             }
    //         }
    //     }
    // }

    /**
     * Права для внесения информации по организации
     * @return bool
     */
    public static function isRoleModerator($code)
    {        
        if (Yii::$app->user->can('admin')) {
            return true;
        }
        if (Yii::$app->user->can('ModeratorOrganizationDepartment-' . $code)) {
            return true;
        }
        return false;
    }


    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        $this->deleteImages($this->getImages());       
        $pathMain = Yii::getAlias('@webroot') . $this->getPathUploadImages();     
        if (is_dir($pathMain)) {
            FileHelper::removeDirectory($pathMain);
        }
        parent::afterDelete();      
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (!$insert) {
            if ($this->deleteImages) {
                $this->deleteImages($this->deleteImages);
            }
        }
    }    


    /** BEGIN UPLOAD */
    
    /**
     * Загрузка изображения
     */
    public function upload()
    {
        if ($this->uploadImages) {
            $this->deleteImages($this->getImages());
        }
        (new UploadHelper($this->getPathUploadImages()))
            ->uploadFiles($this->uploadImages);
    }

    /**
     * @return string
     */
    private function getPathUploadImages()
    {
        return str_replace('{code}', $this->code, Yii::$app->params['organization']['path']['uploadImages']);
    }

    /**
     * Изображения
     * @return string[]
     */
    public function getImages()
    {
        return $this->prepareFiles($this->getPathUploadImages());
    }

    /**
     * Подготовка файлов
     * @param string
     * @return array
     */
    private function prepareFiles($path)
    {        
        $files = $this->searchFiles(Yii::getAlias('@webroot') . $path);
        $result = [];
        if ($files && is_array($files) && count($files)) {
            foreach($files as $file) {
                $result[$path . basename($file)] = $path . basename($file);
            }         
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
     * имена файлов исполнения протоколов для удаления
     * если параметр $files не передается, то удаляются все файлы
     * @param string[] $files
     */
    public function deleteImages($files = [])
    {        
        if ($files && count($files)) {
            foreach($files as $file) {
                FileHelper::unlink(Yii::getAlias('@webroot') . $file);
            }
        }
    }



    /** END UPLOAD */

}
