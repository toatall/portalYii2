<?php

namespace app\modules\kadry\models\education;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%kadry_education_user_data_files}}".
 *
 * @property int $id
 * @property int $id_kadry_education_user_data
 * @property int $id_kadry_education_data_files
 * @property string $date_create
 *
 * @property EducationDataFiles $educationDataFiles
 * @property EducationUserData $educationUserData
 */
class EducationUserDataFiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%kadry_education_user_data_files}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_kadry_education_user_data', 'id_kadry_education_data_files'], 'required'],
            [['id_kadry_education_user_data', 'id_kadry_education_data_files'], 'integer'],
            [['date_create'], 'safe'],
            [['username'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['username' => 'username']],
            [['id_kadry_education_data_files'], 'exist', 'skipOnError' => true, 'targetClass' => EducationDataFiles::class, 'targetAttribute' => ['id_kadry_education_data_files' => 'id']],
            [['id_kadry_education_user_data'], 'exist', 'skipOnError' => true, 'targetClass' => EducationUserData::class, 'targetAttribute' => ['id_kadry_education_user_data' => 'id']],
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
                'updatedAtAttribute' => null,              
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
            'id_kadry_education_user_data' => 'Id Kadry Education User Data',
            'id_kadry_education_data_files' => 'Id Kadry Education Data Files',
            'date_create' => 'Date Create',
        ];
    }

    /**
     * Gets query for [[EducationDataFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducationDataFiles()
    {
        return $this->hasOne(EducationDataFiles::class, ['id' => 'id_kadry_education_data_files']);
    }

    /**
     * Gets query for [EducationUserData]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducationUserData()
    {
        return $this->hasOne(EducationUserData::class, ['id' => 'id_kadry_education_user_data'])
            ->where(['username' => Yii::$app->user->identity->username]);
    }
}
