<?php

namespace app\models\telephone;

use Yii;

/**
 * This is the model class for table "{{%telephone_user}}".
 *
 * @property string $unid
 * @property string $unid_department
 * @property string $fio
 * @property string|null $telephone
 * @property string|null $telephone_dop
 * @property string|null $location
 * @property string|null $mail
 * @property string|null $department_name
 * @property string|null $post
 * @property string|null $notes_name
 * @property string|null $index
 * @property string $date_create
 * @property string $date_update
 * @property string|null $author
 */
class TelephoneUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%telephone_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unid', 'unid_department', 'fio', 'date_create', 'date_update'], 'required'],
            [['department_name'], 'string'],
            [['date_create', 'date_update'], 'safe'],
            [['unid', 'unid_department'], 'string', 'max' => 32],
            [['fio'], 'string', 'max' => 500],
            [['telephone', 'telephone_dop', 'mail', 'notes_name'], 'string', 'max' => 300],
            [['location'], 'string', 'max' => 100],
            [['post'], 'string', 'max' => 200],
            [['index'], 'string', 'max' => 50],
            [['author'], 'string', 'max' => 250],
            [['unid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'unid' => 'Unid',
            'unid_department' => 'Unid Department',
            'fio' => 'Fio',
            'telephone' => 'Telephone',
            'telephone_dop' => 'Telephone Dop',
            'location' => 'Location',
            'mail' => 'Mail',
            'department_name' => 'Department Name',
            'post' => 'Post',
            'notes_name' => 'Notes Name',
            'index' => 'Index',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'author' => 'Author',
        ];
    }
}
