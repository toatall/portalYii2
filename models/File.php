<?php

namespace app\models;

use app\behaviors\AuthorBehavior;
use Yii;

/**
 * This is the model class for table "{{%file}}".
 *
 * @property int $id
 * @property string|null $id_organization
 * @property int $id_model
 * @property string $model
 * @property string $file_name
 * @property string|null $full_filename
 * @property int|null $file_size
 * @property string $date_create
 * @property int|null $count_download
 * @property string $author
 *
 * @property User $author0
 * @property Organization $organization
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%file}}';
    }

    /**
     * {@inheritDoc}
     * @return array
     */
    public function behaviors()
    {
        return [
            AuthorBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_name'], 'required'],
            [['id_model', 'file_size', 'count_download'], 'integer'],
            [['date_create'], 'safe'],
            [['id_organization'], 'string', 'max' => 5],
            [['model'], 'string', 'max' => 50],
            [['file_name', 'author'], 'string', 'max' => 250],
            [['full_filename'], 'string', 'max' => 500],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author' => 'username_windows']],
            [['id_organization'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['id_organization' => 'code']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_organization' => 'Id Organization',
            'id_model' => 'Id Model',
            'model' => 'Model',
            'file_name' => 'File Name',
            'full_filename' => 'Full Filename',
            'file_size' => 'File Size',
            'date_create' => 'Date Create',
            'count_download' => 'Count Download',
            'author' => 'Author',
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

    /**
     * Gets query for [[Organization]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::class, ['code' => 'id_organization']);
    }

    /**
     * {@inheritDoc}
     */
    public function afterDelete()
    {
        \Yii::$app->storage->deleteFile($this->file_name);
        parent::afterDelete();
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->id_organization = Yii::$app->userInfo->current_organization;
        return parent::beforeSave($insert);
    }
}
