<?php

namespace app\modules\kadry\models\education;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use Yii;

/**
 * This is the model class for table "{{%kadry_education_data_files}}".
 *
 * @property int $id
 * @property int $id_kadry_education_data
 * @property string $filename
 * @property string|null $title
 * @property string $date_create
 *
 * @property EducationData $educationData
 * @property EducationUserDataFiles $educationUserDataFiles
 */
class EducationDataFiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%kadry_education_data_files}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_kadry_education_data', 'filename'], 'required'],
            [['id_kadry_education_data'], 'integer'],
            [['date_create'], 'safe'],
            [['filename'], 'string', 'max' => 500],
            [['title'], 'string', 'max' => 250],
            [['id_kadry_education_data'], 'exist', 'skipOnError' => true, 'targetClass' => EducationData::class, 'targetAttribute' => ['id_kadry_education_data' => 'id']],
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
            'id_kadry_education_data' => 'Id Kadry Education Data',
            'filename' => 'Filename',
            'title' => 'Title',
            'date_create' => 'Date Create',
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
     * Gets query for [[EducationUserDataFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducationUserDataFiles()
    {
        return $this->hasOne(EducationUserDataFiles::class, ['id_kadry_education_data_files' => 'id'])            
            ->where(['username' => Yii::$app->user->identity->username]);
    }

    /**
     * Сохранение информации о закачке
     */
    public function saveDownloadVisit()
    {
        $this->checkData();
        $model = EducationUserDataFiles::find()->where([
            'id_kadry_education_user_data' => $this->educationData->id,
            'id_kadry_education_data_files' => $this->id,
            'username' => Yii::$app->user->identity->username,
        ])->one();        
        if ($model === null) {
            $model = new EducationUserDataFiles([
                'id_kadry_education_user_data' => $this->educationData->educationUserDatas->id,
                'id_kadry_education_data_files' => $this->id,
            ]);   
            $model->save();            
            $model->educationUserData->updatePercent();
        }
    }        

    /**
     * Проверка наличия раздела data
     * Если нет, то он создается
     */
    private function checkData()
    {
        if ($this->educationData->educationUserDatas == null) {
            $modelUser = $this->educationUserDataFiles->educationUserData;
            $modelEducationUserData = new EducationUserData([
                'id_kadry_education_user' => $modelUser->id,
                'id_kadry_education_data' => $this->educationData->id,
            ]);
            $modelEducationUserData->save();
            $modelUser->link('educationUserDatas', $modelEducationUserData);
        }
    }

}
