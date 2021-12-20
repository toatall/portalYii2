<?php

namespace app\models\telephone;

use Yii;

/**
 * This is the model class for table "{{%telephone_department}}".
 *
 * @property string $unid
 * @property string|null $unid_parent
 * @property string $form
 * @property string|null $org_code
 * @property string|null $index
 * @property string|null $name
 * @property string|null $full_name
 * @property string|null $leader
 * @property string|null $phone
 * @property string|null $fax
 * @property string|null $address
 * @property string|null $mail
 * @property string $date_create
 * @property string $date_update
 * @property string|null $author
 */
class TelephoneDepartment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%telephone_department}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unid', 'form', 'date_create', 'date_update'], 'required'],
            [['name', 'full_name'], 'string'],
            [['date_create', 'date_update'], 'safe'],
            [['unid', 'unid_parent'], 'string', 'max' => 32],
            [['form'], 'string', 'max' => 30],
            [['org_code'], 'string', 'max' => 5],
            [['index'], 'string', 'max' => 10],
            [['leader'], 'string', 'max' => 500],
            [['phone', 'fax', 'address', 'mail'], 'string', 'max' => 300],
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
            'unid_parent' => 'Unid Parent',
            'form' => 'Form',
            'org_code' => 'Org Code',
            'index' => 'Index',
            'name' => 'Name',
            'full_name' => 'Full Name',
            'leader' => 'Leader',
            'phone' => 'Phone',
            'fax' => 'Fax',
            'address' => 'Address',
            'mail' => 'Mail',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'author' => 'Author',
        ];
    }
}
