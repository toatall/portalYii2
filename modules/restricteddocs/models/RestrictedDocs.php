<?php

namespace app\modules\restricteddocs\models;

use app\behaviors\AuthorBehavior;
use app\helpers\UploadHelper;
use app\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%restricted_docs}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $doc_num
 * @property string|null $doc_date
 * @property string|null $privacy_sign_desc
 * @property string|null $owner
 * @property int $date_create
 * @property int $date_update
 * @property string $author
 *
 * @property User $authorModel
 * @property RestrictedDocsOrgs[] $restrictedDocsOrgs
 * @property RestrictedDocsTypes[] $restrictedDocsTypes
 */
class RestrictedDocs extends \yii\db\ActiveRecord
{

    /**
     * @var array|null
     */
    public $restrictedDocsOrgsVals;

    /**
     * @var array|null
     */
    public $restrictedDocsTypesVals;


    /**
     * @var yii\web\UploadedFile[]
     */
    public $uploadFiles;

    /**
     * Файлы отмеченные для удаления
     * @var string[]
     */
    public $deleteFiles;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%restricted_docs}}';
    }

    /**
     * @return string
     */
    public static function roleModerator()
    {
        return Yii::$app->params['modules']['restricteddocs']['roles']['moderator'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'restrictedDocsOrgsVals', 'restrictedDocsTypesVals'], 'required'],
            [['doc_date'], 'safe'],
            [['privacy_sign_desc'], 'string'],
            [['date_create', 'date_update'], 'integer'],
            [['name'], 'string', 'max' => 1000],
            [['doc_num'], 'string', 'max' => 200],
            [['owner'], 'string', 'max' => 2000],
            [['author'], 'string', 'max' => 250],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
            [['uploadFiles'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 30],
            [['deleteFiles'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'name' => 'Наименование НПА',
            'doc_num' => 'Номер НПА',
            'doc_date' => 'Дата НПА',
            'privacy_sign_desc' => 'Признак конфиденциальности',
            'owner' => 'Владелец НПА',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'author' => 'Автор',
            'restrictedDocsOrgsVals' => 'Перечень организаций, в отношении  которых  в НПА  описано предоставление сведений',
            'restrictedDocsTypesVals' => 'Описание информации, регламентируемой НПА',
            'uploadFiles' => 'Файлы',
            'deleteFiles' => 'Отметьте файлы для удаления',
        ];
    }

    /**
     * {@inheritdoc}
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
     * @return \yii\db\ActiveQuery
     */
    public function getRestrictedDocsOrgs()
    {
        return $this->hasMany(RestrictedDocsOrgs::class, ['id' => 'id_org'])
            ->viaTable('{{%restricted_docs_orgs__restricted_docs}}', ['id_doc' => 'id']);
    }

    /**
     * @param array|null $values
     */
    public function setRestrictedDocsOrgs($values)
    {       
        $this->restrictedDocsOrgsVals = $values;        
    }

    /**
     * @param array|null $values
     */
    public function setRestrictedDocsTypes($values)
    {
        $this->restrictedDocsTypesVals = $values;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestrictedDocsTypes()
    {
        return $this->hasMany(RestrictedDocsTypes::class, ['id' => 'id_type'])
            ->viaTable('{{%restricted_docs_types__restricted_docs}}', ['id_doc' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // remove all links
        $this->unlinkAll('restrictedDocsOrgs', true);

        if ($this->restrictedDocsOrgsVals) {
        
            // add new links
            foreach($this->restrictedDocsOrgsVals as $value) {
                $model = RestrictedDocsOrgs::findOne($value);
                if ($model != null) {
                    $this->link('restrictedDocsOrgs', $model);
                }
            }
        }
        
        // remove all links
        $this->unlinkAll('restrictedDocsTypes', true);

        if ($this->restrictedDocsTypesVals) {
            
            // add new links
            foreach($this->restrictedDocsTypesVals as $value) {
                $model = RestrictedDocsTypes::findOne($value);
                if ($model != null) {
                    $this->link('restrictedDocsTypes', $model);
                }
            }
        }

        if (!$insert) {
            $this->deleteFiles($this->deleteFiles);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        $this->restrictedDocsOrgsVals = $this->restrictedDocsOrgs;
        $this->restrictedDocsTypesVals = $this->restrictedDocsTypes;
    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        $this->deleteFiles(null, true);
    }


    /**
     * -----------------------------------------------------------------
     *                         BEGIN UPLOAD FUNCTIONS
     * -----------------------------------------------------------------
     */

    /**
     * Upload files
     */
    public function upload()
    {   
        (new UploadHelper($this->getPathFiles()))
            ->uploadFiles($this->uploadFiles);       
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        $path = $this->getPathFiles();
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
     * The path for upload
     * @return string
     */
    protected function getPathFiles()
    {
        return str_replace('{id}', $this->id, \Yii::$app->params['modules']['restricteddocs']['uploadPath']);
    }

    /**
     * Delete files from the disk
     * @param array|null $files
     */
    public function deleteFiles($files = null, $deleteAll = false)
    {
        if ($deleteAll) {
            $files = $this->getFiles();
        }
        if ($files) {
            foreach ($files as $file) {
                FileHelper::unlink(Yii::getAlias('@webroot') . $file);
            }
        }
        if ($deleteAll) {
            FileHelper::removeDirectory($this->getPathFiles());
        }
    }
  
    /**
     * -----------------------------------------------------------------
     *                         END UPLOAD FUNCTIONS
     * -----------------------------------------------------------------
     */


}
