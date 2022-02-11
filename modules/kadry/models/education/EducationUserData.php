<?php

namespace app\modules\kadry\models\education;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%kadry_education_user_data}}".
 *
 * @property int $id
 * @property int $id_kadry_education_user
 * @property int $id_kadry_education_data
 * @property float|null $percent
 * @property string $date_create
 * @property string $date_update
 *
 * @property EducationData $educationData
 * @property EducationUser $educationUser * @property EducationUserDataFiles[] $educationUserDataFiles
 */
class EducationUserData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%kadry_education_user_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_kadry_education_user', 'id_kadry_education_data'], 'required'],
            [['id_kadry_education_user', 'id_kadry_education_data'], 'integer'],
            [['percent'], 'number'],
            [['date_create', 'date_update'], 'safe'],
            [['username'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['username' => 'username']],
            [['id_kadry_education_data'], 'exist', 'skipOnError' => true, 'targetClass' => EducationData::class, 'targetAttribute' => ['id_kadry_education_data' => 'id']],
            [['id_kadry_education_user'], 'exist', 'skipOnError' => true, 'targetClass' => EducationUser::class, 'targetAttribute' => ['id_kadry_education_user' => 'id']],
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
            [
                'class' => AuthorBehavior::class, 
                'author_at' => 'username',
            ],            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_kadry_education_user' => 'Id Kadry Education User',
            'id_kadry_education_data' => 'Id Kadry Education Data',
            'percent' => 'Percent',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
        ];
    }

    /**
     * Gets query for [[EducationData]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducationData()
    {
        return $this->hasOne(EducationData::class, ['id' => 'id_kadry_education_data']);
    }

    /**
     * Gets query for [[EducationUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducationUser()
    {
        return $this->hasOne(EducationUser::class, ['id' => 'id_kadry_education_user']);
    }

    /**
     * Gets query for [[EducationUserDataFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducationUserDataFiles()
    {
        return $this->hasMany(EducationUserDataFiles::class, ['id_kadry_education_user_data' => 'id']);
    }

    public function getCountStudyFiles()
    {
        $count = $this->getEducationUserDataFiles()->count();
        foreach ($this->educationData->educationChildrenDatas as $data) {
            $count += $data->educationUserDatas->getCountStudyFiles();
        }
        return $count;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($insert) {
            $this->percent = 0;                     
        }
        else {
            $this->touch('date_update');        
        }
        return true;
    }

    /**
     * Обновление процентов изученного материала
     */
    public function updatePercent()
    {        
        $countAllFiles = $this->educationData->getCountFiles();
        $countStudyFiles = $this->getCountStudyFiles(); //$this->getEducationUserDataFiles()->count();
        $this->percent = $countStudyFiles / $countAllFiles * 100;        
        $this->save(false, ['percent']);        
        if ($this->educationData->educationParentData != null) {
            $this->educationData->educationParentData->educationUserDatas->updatePercent();
        }
        else {
            $this->educationUser->updatePercent();
        }
    }

}
