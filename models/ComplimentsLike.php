<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%compliments_like}}".
 *
 * @property string $file_name
 * @property string $username
 * @property string|null $date_create
 */
class ComplimentsLike extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%compliments_like}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_name', 'username'], 'required'],
            [['date_create'], 'safe'],
            [['file_name'], 'string', 'max' => 500],
            [['username'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'file_name' => 'File Name',
            'username' => 'Username',
            'date_create' => 'Date Create',
        ];
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
     */
    public function beforeSave($insert)
    {
        if ((new Query())
            ->from(static::tableName())
            ->where([
                'username' => Yii::$app->user->identity->username,
                'file_name' => $this->file_name,
            ])
            ->exists()) {
            return false;
        }
        return parent::beforeSave($insert);
    }

    
}
