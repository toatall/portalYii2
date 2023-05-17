<?php

namespace app\modules\admin\modules\grantaccess\models;

use app\behaviors\AuthorBehavior;
use app\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;

/**
 * This is the model class for table "{{%grant_access_group}}".
 *
 * @property int $id
 * @property string $unique
 * @property string $title
 * @property string $note
 * @property int|null $date_create
 * @property int|null $date_update
 * @property string|null $author
 *
 * @property User $authorModel 
 * @property User[] $users
 * @property GrantAccessGroupAdGroup[] $adGroups
 */
class GrantAccessGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%grant_access_group}}';
    }

    /**
     * {@inheritDoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
            ],
            [
                'class' => AuthorBehavior::class,                
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unique', 'title'], 'required'],
            [['date_create', 'date_update'], 'integer'],
            [['unique'], 'string', 'max' => 50],
            [['title'], 'string', 'max' => 200],
            [['author'], 'string', 'max' => 250],
            [['note'], 'string'],
            [['unique'], 'unique'],
            [['author'], 'exist', 'skipOnError' => true, 
                'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'unique' => 'Идентификатор',
            'title' => 'Заголовок',
            'note' => 'Описание',            
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
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'id_user'])
            ->viaTable('{{%grant_access_group__user}}', ['id_group' => 'id']);            
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdGroups()
    {
        return $this->hasMany(GrantAccessGroupAdGroup::class, ['id_group' => 'id']);
    }
    
    /**
     * Включение пользователя в группу
     * @param int $id идентификатор пользователя
     * @return int|null
     */
    public function assignUser($id)
    {
        if (!(new Query())
            ->from('{{%grant_access_group__user}}')
            ->where([
                'id_group' => $this->id,
                'id_user' => $id,
            ])
            ->exists()
        ) {            
            Yii::$app->grantAccess->clearUsersCache($this->unique);
            return Yii::$app->db->createCommand()
                ->insert('{{%grant_access_group__user}}', [
                    'id_group' => $this->id,
                    'id_user' => $id,
                    'date_create' => time(),
                ])
                ->execute();
        }
        else {
            return null;
        }            
    }

    /**
     * Отзыв пользователя из группы
     * @param int $id идентификатор пользователя
     * @return bool
     */
    public function revokeUser($id)
    {
        $res = Yii::$app->db->createCommand()
            ->delete('{{%grant_access_group__user}}', [
                'id_group' => $this->id,
                'id_user' => $id,
            ])->execute();
        if ($res > 0) {          
            Yii::$app->grantAccess->clearUsersCache($this->unique);
            return true;
        }
        return false;
    }    

    /**
     * {@inheritDoc}
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }
        // отзыв у всех пользователей доступа удалением кэша
        return Yii::$app->grantAccess->clearUsersCache($this->unique);
    }
  
}
