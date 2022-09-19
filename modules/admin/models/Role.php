<?php
namespace app\modules\admin\models;

use app\models\User;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use function foo\func;

/**
 * Class Role
 * @package app\modules\admin\models
 * @property string $name
 * @property string $description
 * @property string $rule_name
 * @property string $created_at
 * @property string $updated_at
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%auth_item}}';
    }
    
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Наименование роли',
            'description' => 'Описание',
            'rule_name' => 'Правило',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',            
        ];
    }

    /**
     * {@inheritDoc}
     * @return array|array[]
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'unique'],
            [['description'], 'string'],
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()->where(['type'=> \yii\rbac\Item::TYPE_ROLE]);
    }


    /**
     * @return mixed
     */
    private function getPaginzationSize()
    {
        return isset(\Yii::$app->params['role']['pageSize']) 
            ? \Yii::$app->params['role']['pageSize'] : \Yii::$app->params['pageSize'];
    }

    /**
     * Сохранение роли
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws \Exception
     */
    public function save($runValidation = true, $attributeNames = NULL)
    {
        if (!$this->validate()) {
            return false;
        }
        /* @var $auth \yii\rbac\DbManager */
        $auth = \Yii::$app->authManager;
        $role = $auth->createRole($this->name);
        $role->description = $this->description;
        return $auth->add($role);
    }

    /**
     * Пользователи, входяшие в текущую роль
     * @return Query
     */
    public function getChildUsers()
    {
        /* @var $auth \yii\rbac\DbManager */
        $auth = \Yii::$app->authManager;
        $ids = $auth->getUserIdsByRole($this->name);
        return User::find()->where(['in', 'id', $ids]);
    }

    /**
     * @return ActiveDataProvider
     */
    public function getChildUserDataProvider()
    {
        return new ActiveDataProvider([
            'query' => $this->getChildUsers(),
        ]);
    }

    /**
     * Роли, входящие в текущую роль
     * @return \yii\rbac\Role[]
     */
    public function getChildGroups()
    {
        /* @var $auth \yii\rbac\DbManager */
        $auth = \Yii::$app->authManager;
        $roles = $auth->getChildRoles($this->name);
        if (isset($roles[$this->name])) {
            unset($roles[$this->name]);
        }
        $resultRoles = [];
        foreach ($roles as $role) {
            $resultRoles[] = $role->name;
        }
        return $resultRoles;
    }

    /**
     * @return ActiveDataProvider
     */
    public function getChildRolesDataProvider()
    {
        return new ActiveDataProvider([
            'query' => self::find()->where(['in', 'name', $this->getChildGroups()]),
        ]);
    }

    /**
     * Список пользователей, кому возможно назначение роли
     * @return ActiveDataProvider
     */
    public function getUsersForAddRole()
    {
        // получение списка пользователей входящих в текущую роль
        /* @var $auth \yii\rbac\DbManager */
        $auth = \Yii::$app->authManager;
        $userIds = $auth->getUserIdsByRole($this->name);
        // выбрать всех пользователей, за исключением $userIds
        $model = User::find()->where(['not in', 'id', $userIds])
            ->andWhere(['blocked'=>'0', 'date_delete'=>null]);

        return new ActiveDataProvider([
            'query' => $model,
        ]);
    }

    public function getRolesForAddRole()
    {
        // получение списка ролей входящих в текущую роль
        /* @var $auth \yii\rbac\DbManager */
        $auth = \Yii::$app->authManager;
        $roles = $auth->getChildRoles($this->name);
        $roles = array_keys($roles);
        // выбрать всех пользователей, за исключением $userIds
        $model = self::find()->andWhere(['not in', 'name', $roles]);

        return new ActiveDataProvider([
            'query' => $model,
        ]);
    }

    
    
}

 ?>
