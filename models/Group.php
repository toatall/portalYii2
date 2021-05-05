<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%group}}".
 *
 * @property int $id
 * @property string $id_organization
 * @property string $name
 * @property string|null $description
 * @property int $sort
 * @property string $date_create
 * @property string $date_edit
 *
 * @property Organization $organization
 * @property GroupUser[] $groupUsers
 */
class Group extends \yii\db\ActiveRecord
{
    /**
     * @var array
     */
    private $users;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_organization', 'name'], 'required'],
            [['description'], 'string'],
            [['sort'], 'integer'],
            [['date_create', 'date_edit'], 'safe'],
            [['id_organization'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 250],
            [['id_organization'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['id_organization' => 'code']],
//            [['name'], 'on' => 'search', 'safe'],
            [['groupUsers'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'id_organization' => 'Организация',
            'name' => 'Наименование',
            'description' => 'Описание',
            'sort' => 'Сортировка',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата изменения',
        ];
    }

    /**
     * Поиск
     * @param array $params
     * @param int|null $excludeId
     * @return ActiveDataProvider
     */
    public function search($params, $excludeId = null)
    {
        $query = self::find();
        if ($excludeId) {
            $query->andWhere(['not in', 'id', explode(',', $excludeId)]);
        }
        $query->andWhere(['id_organization'=>Yii::$app->userInfo->current_organization]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params); //?
        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
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
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getGroupUsers()
    {
        return $this->hasMany(User::class, ['id' => 'id_user'])
            ->viaTable('{{%group_user}}', ['id_group' => 'id']);
    }

    /**
     * @param $values
     */
    public function setGroupUsers($values)
    {
        $this->users = $values;
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->id_organization = Yii::$app->userInfo->current_organization;
        return true;
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \yii\db\Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $command = Yii::$app->db->createCommand();
        // delete relations
        $command->delete('{{%group_user}}', [
            'id_group' => $this->id,
        ])->execute();
        // save users
        if (is_array($this->users) && count($this->users) > 0) {
            foreach ($this->users as $user) {
                $command->insert('{{%group_user}}', [
                    'id_group' => $this->id,
                    'id_user' => $user,
                ])->execute();
            }
        }
    }

    /**
     * Список пользователей в текущей группе
     * @return array
     * @uses in file 'modules/admin/views/group/_form.php'
     */
    public function getListGroupUsers()
    {
        $result = [];
        foreach ($this->groupUsers as $val)
        {
            $result[$val->id] = $val->concat;
        }
        return $result;
    }


}
