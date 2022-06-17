<?php

namespace app\modules\kadry\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use app\helpers\UploadHelper;
use Yii;
use app\models\Organization;
use app\models\User;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "p_best_professional".
 *
 * @property int $id
 * @property string $org_code
 * @property string $period
 * @property int $period_year
 * @property string $department
 * @property string $fio
 * @property string $description
 * @property string $date_create
 * @property string $date_update
 * @property string $author
 * @property string|null $log_change
 * @property string $nomination
 *
 * @property Organization $orgCode
 * @property User $authorModel
 */
class BestProfessional extends \yii\db\ActiveRecord
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


    private static $periods = [        
        // кварталы
        '03_2_kv' => '1 квартал',
        '06_2_kv' => '2 квартал',
        '09_2_kv' => '3 квартал',
        '12_2_kv' => '4 квартал',        
    ];

    /**
     * Периоды
     * @return array
     */
    public static function periods()
    {
        return self::$periods;
    }

    public static function getPeriodNameByCode($code)
    {
        return isset(self::periods()[$code]) ? self::periods()[$code] : $code;
    }

    /**
     * Периоды (годы)
     * @return array
     */
    public static function periodsYears()
    {
        $years = [];
        $currentY = date('Y');
        for ($i=$currentY-2; $i<=$currentY+2; $i++) {
            $years[$i] = $i;
        }
        return $years;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%best_professional}}';
    }

    /**
     * @return string
     */
    public static function roleModerator()
    {
        return Yii::$app->params['modules']['kadry']['best-professional']['roles']['moderator'];
    }

    /**
     * @return boolean
     */
    public static function isEditor()
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
            [['org_code', 'period', 'period_year', 'department', 'fio', 'description', 'nomination'], 'required'],
            [['period_year'], 'integer'],
            [['description', 'log_change'], 'string'],
            [['date_create', 'date_update'], 'safe'],
            [['org_code'], 'string', 'max' => 5],
            [['period'], 'string', 'max' => 30],
            [['department', 'fio', 'nomination'], 'string', 'max' => 500],
            [['author'], 'string', 'max' => 250],
            [['org_code'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::class, 'targetAttribute' => ['org_code' => 'code']],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
            [['uploadImage'], 'file', 'skipOnEmpty' => true],
            [['deleteImage'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'org_code' => 'Налоговый орган',
            'period' => 'Период',
            'period_year' => 'Год',
            'department' => 'Отдел',
            'fio' => 'ФИО',
            'description' => 'Описание',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'author' => 'Author',
            'log_change' => 'Log Change',
            'deleteImage' => 'Удалить изображение',
            'nomination' => 'Номинация',
        ];
    }

    /**
     * Gets query for [[OrgCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrgCode()
    {
        return $this->hasOne(Organization::class, ['code' => 'org_code']);
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
     * Загрузка изображения
     */
    public function upload()
    {
        if ($this->uploadImage) {
            $this->deleteImageFile($this->getImage());
        }
        (new UploadHelper($this->getPathUploadFile()))
            ->uploadFiles($this->uploadImage);
    }

    /**
     * @return string
     */
    private function getPathUploadFile()
    {
        return str_replace('{id}', $this->id, Yii::$app->params['modules']['kadry']['best-professional']['path']['images']);
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
