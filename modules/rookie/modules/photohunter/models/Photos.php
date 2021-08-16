<?php

namespace app\modules\rookie\modules\photohunter\models;

use Yii;
use app\models\department\Department;

/**
 * This is the model class for table "{{%rookie_photohunter_photos}}".
 *
 * @property int $id
 * @property string $code_no
 * @property int $id_department|null
 * @property string $image
 * @property string $thumb
 * @property string|null $nomination
 * @property string|null $title
 * @property string|null $description
 * @property string|null $date_create
 *
 * @property Department $department
 * @property PhotosVotes[] $photosVotes
 * @property PhotosVote $photoVotesActiveUser
 */
class Photos extends \yii\db\ActiveRecord
{
   
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%rookie_photohunter_photos}}';
    }    

    /**
     * Gets query for [[Department]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::class, ['id' => 'id_department']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotosVotes()
    {
        return $this->hasMany(PhotosVotes::class, ['id_photos' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotosVotesActiveUser()
    {
        $query = $this->getPhotosVotes();
        $query->multiple = false;
        return $query->andWhere([
            'username' => Yii::$app->user->identity->username,
        ]);
    }

    /**
     * Возможно ли проголосовать за это изображение
     * @return boolean
     */
    public function canVote()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        $match = null;
        preg_match('/^(8600|n8600)(-)/', Yii::$app->user->identity->username, $match);
        if (!isset($match[1])) {
            return false;
        }       
        $userPrefix = $match[1];

        // 1. Если пользователь участник УФНС или ФКУ
        if (!in_array($userPrefix, ['8600', 'n8600'])) {
            return false;
        }        

        // 3. Если текущий пользователь из такой же организации и отдела
        if ($this->isCurrentOrganizationDepartmtent($userPrefix)) {
            return false;
        }

        return true;
    }

    /**
     * Входит ли пользователь в текущий отдел (УФНС) или организацию (ФКУ)
     * @param string $userPrefix
     */
    private function isCurrentOrganizationDepartmtent(string $userPrefix)
    {             
        if ($userPrefix == 'n8600' && $this->code_no == 'n8600') {
            return true;
        }
        if ($userPrefix == '8600' && $this->description == Yii::$app->user->identity->department) {
            return true;
        }
        return false;
    }
    
}
