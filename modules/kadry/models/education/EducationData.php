<?php

namespace app\modules\kadry\models\education;

use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%kadry_education_data}}".
 *
 * @property int $id
 * @property int|null $id_parent
 * @property int $id_kadry_education
 * @property string $name
 * @property string|null $description
 * @property string|null $thumbnail
 * @property string $author
 * @property string $date_create
 * @property string $date_update
 * @property string $log_change
 *
 * @property User $authorModel
 * @property Education $education
 * @property EducationDataFiles[] $educationDataFiles
 * @property EducationData $educationParentData
 * @property EducationData[] $educationChildrenDatas
 * @property EducationUserData $educationUserDatas
 */
class EducationData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%kadry_education_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_parent', 'id_kadry_education'], 'integer'],
            [['id_kadry_education', 'name', 'author', 'date_create', 'date_update', 'log_change'], 'required'],
            [['description', 'log_change'], 'string'],
            [['date_create', 'date_update'], 'safe'],
            [['name', 'thumbnail'], 'string', 'max' => 500],
            [['author'], 'string', 'max' => 250],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
            [['id_kadry_education'], 'exist', 'skipOnError' => true, 'targetClass' => Education::class, 'targetAttribute' => ['id_kadry_education' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_parent' => 'Id Parent',
            'id_kadry_education' => 'Id Kadry Education',
            'name' => 'Name',
            'description' => 'Description',
            'thumbnail' => 'Thumbnail',
            'author' => 'Author',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'log_change' => 'Log Change',
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
     * Gets query for [[Education]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducation()
    {
        return $this->hasOne(Education::class, ['id' => 'id_kadry_education']);
    }

    /**
     * Gets query for [[EducationDataFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducationDataFiles()
    {
        return $this->hasMany(EducationDataFiles::class, ['id_kadry_education_data' => 'id']);
    }

    /**
     * Gets query for [[EducationData]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducationChildrenDatas()
    {
        return $this->hasMany(EducationData::class, ['id_parent' => 'id']);
    }

    /**
     * Gets query for [[EducationData]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducationParentData()
    {
        return $this->hasOne(EducationData::class, ['id' => 'id_parent']);
    }

    /**
     * Gets query for [[EducationUserDatas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducationUserDatas()
    {
        return $this->hasOne(EducationUserData::class, ['id_kadry_education_data' => 'id'])
            ->where(['username' => Yii::$app->user->identity->username]);
    }

    public function getCountFiles()
    {
        $count = $this->getEducationDataFiles()->count();
        foreach ($this->educationChildrenDatas as $data) {
            $count += $data->getCountFiles();
        }
        return $count;
    }

}
