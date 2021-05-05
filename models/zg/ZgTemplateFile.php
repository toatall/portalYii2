<?php

namespace app\models\zg;

use Yii;

/**
 * This is the model class for table "{{%zg_template_file}}".
 *
 * @property int $id
 * @property int $id_zg_template
 * @property string|null $filename
 * @property string $date_create
 *
 * @property ZgTemplate $zgTemplate
 */
class ZgTemplateFile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%zg_template_file}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_zg_template'], 'required'],
            [['id_zg_template'], 'integer'],
            [['filename'], 'string'],
            [['date_create'], 'safe'],
            [['id_zg_template'], 'exist', 'skipOnError' => true, 'targetClass' => ZgTemplate::className(), 'targetAttribute' => ['id_zg_template' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_zg_template' => 'Id Zg Template',
            'filename' => 'Filename',
            'date_create' => 'Date Create',
        ];
    }

    /**
     * Gets query for [[ZgTemplate]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZgTemplate()
    {
        return $this->hasOne(ZgTemplate::className(), ['id' => 'id_zg_template']);
    }
}
