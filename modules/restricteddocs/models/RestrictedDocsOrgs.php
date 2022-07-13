<?php

namespace app\modules\restricteddocs\models;

use app\behaviors\AuthorBehavior;
use app\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%restricted_docs_orgs}}".
 *
 * @property int $id
 * @property string $name
 * @property int $date_create
 * @property int $date_update
 * @property string $author
 *
 * @property User $authorModel
 * @property RestrictedDocsOrgsRestrictedDocs[] $restrictedDocsOrgsRestrictedDocs
 */
class RestrictedDocsOrgs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%restricted_docs_orgs}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['date_create', 'date_update'], 'integer'],
            [['name'], 'string', 'max' => 500],
            [['author'], 'string', 'max' => 250],
            [['name'], 'unique'],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'name' => 'Нименование',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'author' => 'Автор',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [            
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
            ],
            ['class' => AuthorBehavior::class],     
        ];
    }

    /**
     * Gets query for [[AuthorModel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorModel()
    {
        return $this->hasOne(User::class, ['username' => 'author']);
    }

    /**
     * The list for widget Select2
     * @return array
     */
    public static function dropDownList()
    {
        $query = self::find()
            ->orderBy(['name' => SORT_ASC])
            ->all();
        return ArrayHelper::map($query, 'id', 'name');
    }

    /**
     * Gets query for [[RestrictedDocsOrgsRestrictedDocs]].
     *
     * @return \yii\db\ActiveQuery
     */
    // public function getRestrictedDocsOrgsRestrictedDocs()
    // {
    //     return $this->hasMany(RestrictedDocsOrgsRestrictedDocs::className(), ['id_org' => 'id']);
    // }
}
