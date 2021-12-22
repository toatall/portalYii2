<?php

namespace app\models\lifehack;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use Yii;

/**
 * This is the model class for table "{{%lifehack_file}}".
 *
 * @property int $id
 * @property int $id_lifehack
 * @property string $filename
 * @property string|null $file_type_icon
 * @property string|null $date_create
 * @property int|null $count_download
 * @property string|null $username
 *
 * @property Lifehack $lifehack
 * @property LifehackFileDownload[] $lifehackFileDownloads
 */
class LifehackFile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lifehack_file}}';
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
                'updatedAtAttribute' => null,
            ],
            [
                'class' => AuthorBehavior::class,
                'author_at' => 'usernname',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_lifehack', 'filename'], 'required'],
            [['id_lifehack', 'count_download'], 'integer'],
            [['date_create'], 'safe'],
            [['filename'], 'string', 'max' => 500],
            [['file_type_icon'], 'string', 'max' => 15],
            [['username'], 'string', 'max' => 250],
            [['id_lifehack'], 'exist', 'skipOnError' => true, 'targetClass' => Lifehack::class, 'targetAttribute' => ['id_lifehack' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_lifehack' => 'Id Lifehack',
            'filename' => 'Filename',
            'file_type_icon' => 'File Type Icon',
            'date_create' => 'Date Create',
            'count_download' => 'Count Download',
            'username' => 'username',
        ];
    }

    /**
     * Gets query for [[Lifehack]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLifehack()
    {
        return $this->hasOne(Lifehack::class, ['id' => 'id_lifehack']);
    }

    
}
