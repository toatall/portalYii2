<?php

namespace app\models\zg;

use Yii;
use app\models\User;

/**
 * This is the model class for table "{{%email_goverment}}".
 *
 * @property int $id
 * @property string $org_name
 * @property string|null $ruk_name
 * @property string|null $telephone
 * @property string $email
 * @property string|null $post_address
 * @property string $date_create
 * @property string $date_edit
 * @property string $author
 *
 * @property User $author0
 */
class EmailGoverment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%email_goverment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['org_name', 'email', 'author'], 'required'],
            [['post_address'], 'string'],
            [['date_create', 'date_edit'], 'safe'],
            [['org_name', 'ruk_name'], 'string', 'max' => 1000],
            [['telephone'], 'string', 'max' => 200],
            [['email'], 'string', 'max' => 500],
            [['author'], 'string', 'max' => 250],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username_windows']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'org_name' => 'Организация',
            'ruk_name' => 'Руководство',
            'telephone' => 'Телефон приемной',
            'email' => 'Электронный адрес',
            'post_address' => 'Почтовый адрес',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата изменения',
            'author' => 'Автор',
        ];
    }

    /**
     * Gets query for [[Author0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor0()
    {
        return $this->hasOne(User::class, ['username_windows' => 'author']);
    }


}
