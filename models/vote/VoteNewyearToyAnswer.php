<?php

namespace app\models\vote;

use Yii;
use yii\db\Query;
use yii\web\HttpException;

/**
 * This is the model class for table "{{%vote_newyear_toy_answer}}".
 *
 * @property int $id
 * @property int $id_vote_newyear_toy
 * @property string $username
 * @property string|null $date_create
 *
 * @property VoteNewyearToy $voteNewyearToy
 */
class VoteNewyearToyAnswer extends \yii\db\ActiveRecord
{
    /**
     * @var array
     */
    private $userInfo;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%vote_newyear_toy_answer}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_vote_newyear_toy', 'username'], 'required'],
            [['id_vote_newyear_toy'], 'integer'],
            [['date_create'], 'safe'],
            [['username'], 'string', 'max' => 250],
            [['id_vote_newyear_toy'], 'exist', 'skipOnError' => true, 'targetClass' => VoteNewyearToy::class, 'targetAttribute' => ['id_vote_newyear_toy' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'id_vote_newyear_toy' => '# root',
            'username' => 'Логин',
            'date_create' => 'Дата создания',
        ];
    }

    /**
     * Gets query for [[VoteNewyearToy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoteNewyearToy()
    {
        return $this->hasOne(VoteNewyearToy::class, ['id' => 'id_vote_newyear_toy']);
    }

    /**
     * {@inheritDoc}
     * @return bool
     */
    public function beforeValidate()
    {
        $this->username = Yii::$app->user->identity->username;
        return parent::beforeValidate();
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @return bool
     * @throws HttpException
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $username = Yii::$app->user->identity->username;

        // Условия сохранения ответа:
        // 0. Если не УФНС, МРИ1 или ФКУ, то false
        //if ($this->isOtherIfns()) {
        //    return false;
        //}

        // 1. Если учетка в массиве VoteNewyearToy::unlimitVoted, то голосовать можно без ограничений
        if (in_array($username, VoteNewyearToy::unlimitVoted())) {
            return true;
        }

        // 2. Если пользователь уже голосовал, то false
        if ((new Query())->from(self::tableName())->where(['username'=>$username])->exists()) {
            throw new HttpException(599, 'Вы уже голосовали ранее!');
            //return false;
        }

        $userInfo = $this->getUserInfo($username);
        $modelToy = $this->voteNewyearToy;

        // 3. Если это сотрудник МРИ1 и голосует за свою ИФНС, то false
        if ($this->isMri1($username) && $modelToy->code_org == '8601') {
            throw new HttpException(599, 'Вы не можете голосовать за работу вашей ИФНС!');
        }

        // 4. Если это сотрудник ФКУ и голосует за свой ФКУ, то false
        if ($this->isFku($username) && $modelToy->code_org == 'n8600') {
            throw new HttpException(599, 'Вы не можете голосовать за работу вашего ФКУ!');
        }

        // 5. Если это сотрудник УФНС и голосует за свой отдел, то false
        if ($this->isUfns($username) && isset($userInfo['ldap_department'])
            && $userInfo['ldap_department'] == $modelToy->department) {
            throw new HttpException(599, 'Вы не можете голосовать за работу вашего отдела!');
        }

         return true;
    }

    /**
     * @param $username
     * @return array|bool
     */
    public function getUserInfo($username)
    {
        if ($this->userInfo == null) {
            $this->userInfo = (new Query())
                ->from('{{%view_user}}')
                ->where(['username' => $username])
                ->one();
        }
        return $this->userInfo;
    }

    /**
     * @param $username
     * @return false|int
     */
    private function isUfns($username)
    {
        return preg_match('/^8600/', $username);
    }

    /**
     * @param $username
     * @return false|int
     */
    private function isMri1($username)
    {
        return preg_match('/^8601/', $username);
    }

    /**
     * @param $username
     * @return false|int
     */
    private function isFku($username)
    {
        return preg_match('/^n86\d\d/', $username);
    }

    /**
     * @param $username
     * @return bool
     */
    private function isOtherIfns($username)
    {
        return !preg_match('/^(8600|8601|n86\d\d)/', $username);
    }


}
