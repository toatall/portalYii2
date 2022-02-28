<?php

namespace app\modules\kadry\models\education;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%kadry_education_user}}".
 *
 * @property int $id
 * @property int|null $id_kadry_education
 * @property string $username
 * @property float|null $percent
 * @property string $date_create
 * @property string $date_update
 * @property string|null $date_finish
 *
 * @property Education $education
 * @property User $usernameModel
 * @property EducationUserData[] $educationUserDatas
 */
class EducationUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%kadry_education_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_kadry_education'], 'integer'],
            [['username', 'date_create', 'date_update'], 'required'],
            [['percent'], 'number'],
            [['date_create', 'date_update', 'date_finish'], 'safe'],
            [['username'], 'string', 'max' => 250],
            [['id_kadry_education'], 'exist', 'skipOnError' => true, 'targetClass' => Education::class, 'targetAttribute' => ['id_kadry_education' => 'id']],
            [['username'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['username' => 'username']],
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
            'id_kadry_education' => 'Id Kadry Education',
            'username' => 'Username',
            'percent' => 'Percent',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'date_finish' => 'Date Finish',
        ];
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
     * Gets query for [[UsernameModel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsernameModel()
    {
        return $this->hasOne(User::class, ['username' => 'username']);
    }

    /**
     * Gets query for [[EducationUserDatas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducationUserDatas()
    {
        return $this->hasMany(EducationUserData::class, ['id_kadry_education_user' => 'id']);
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
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $this->saveDatas($this->education->educationData);
        }
    }

    /**
     * @param EducationData[] $datas 
     */
    public function saveDatas($datas)
    {
        if (empty($datas) || !is_array($datas)) {
            return;
        }
        foreach ($datas as $data) {
            if (!$this->getEducationUserDatas()->where(['id_kadry_education_data' => $data->id])->exists()) {
                $modelEducationUserData = new EducationUserData([
                    'id_kadry_education_user' => $this->id,
                    'id_kadry_education_data' => $data->id,
                ]);
                $modelEducationUserData->save();
                $this->link('educationUserDatas', $modelEducationUserData);
            }
            $this->saveDatas($data->educationChildrenDatas);
        }
    }

    /**
     * Обновление процентов прохождения курса
     */
    public function updatePercent()
    {
        $countAllFiles = 0;
        foreach ($this->education->educationData as $data) {
            $countAllFiles += $data->getCountFiles(true);
        }
        $countStudyFiles = 0;
        foreach ($this->educationUserDatas as $data) {           
            $countStudyFiles += $data->getCountStudyFiles();
        }
        $this->percent = $countStudyFiles / $countAllFiles * 100;        
        $this->save(false, ['percent']);

    }

}
