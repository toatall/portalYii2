<?php

namespace app\modules\log\models;

use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%log}}".
 *
 * @property int $id
 * @property int|null $level
 * @property string|null $category
 * @property string|null $url
 * @property string|null $statusCode
 * @property string|null $statusText
 * @property string|null $user
 * @property float|null $log_time
 * @property string|null $prefix
 * @property string|null $message
 * 
 * @property User $userModel
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%log}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dbPgsqlLog');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level'], 'default', 'value' => null],
            [['level'], 'integer'],
            [['statusText', 'prefix', 'message'], 'string'],
            [['log_time'], 'number'],
            [['category', 'user'], 'string', 'max' => 255],
            [['url'], 'string', 'max' => 1000],
            [['statusCode'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'level' => 'Level',
            'category' => 'Category',
            'url' => 'Url',
            'statusCode' => 'Status Code',
            'statusText' => 'Status Text',
            'user' => 'User',
            'log_time' => 'Log Time',
            'prefix' => 'Prefix',
            'message' => 'Message',
        ];
    }

    public function getUserModel()
    {
        return $this->hasOne(User::class, ['username' => 'user']);
    }

    public static function truncate()
    {
        return static::getDb()->createCommand()
            ->truncateTable(self::tableName())->execute();
    }
}
