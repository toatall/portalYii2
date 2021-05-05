<?php

namespace app\models;

use Yii;
use app\models\Organization;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * This is the model class for table "p_user".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $username_windows
 * @property string|null $fio
 * @property string|null $default_organization
 * @property string|null $current_organization
 * @property int $role_admin
 * @property int $blocked
 * @property string|null $folder_path
 * @property string|null $telephone
 * @property string|null $post
 * @property string|null $rank
 * @property string|null $about
 * @property string|null $department
 * @property string|null $hash
 * @property string|null $organization_name
 * @property string $date_create
 * @property string $date_edit
 * @property string|null $date_delete
 * @property string[] $roles
 * @property string $memberof
 *
 * @property Department[] $departments
 * @property File[] $files
 * @property FileDownload[] $fileDownloads
 * @property GroupUser[] $groupUsers
 * @property LogAuthenticate[] $logAuthenticates
 * @property Menu[] $menus
 * @property Module[] $modules
 * @property News[] $news
 * @property Telephone[] $telephones
 * @property Tree[] $trees
 * @property Organization[] $organizations
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const USER_UNKNOWN_NAME = 'guest';
    
    public $authKey;
    public $accessToken;
    public $password1;
    public $password2;
    private $tempRoles;
       
    
    /**
     * Использование аутентефикации ldap и ntlm (ввод пароля не требуется)
     */
    public $useLdapAuthenticated;
    
    /**
     * {@inheritdoc}
     */
    public function init() 
    {
        $useLdap = isset(Yii::$app->params['user']['useLdapAuthenticated']) ? Yii::$app->params['user']['useLdapAuthenticated'] : false;
        $this->useLdapAuthenticated = $useLdap;
        parent::init();
    }
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username_windows'], 'required'],
            [['role_admin', 'blocked'], 'integer'],
            [['about'], 'string'],
            [['password1', 'password2'], 'string'],            
            ['password1', 'compare', 'compareAttribute' => 'password2'],
            [['date_create', 'date_edit', 'date_delete', 'roles', 'memberof'], 'safe'],
            [['username', 'username_windows', 'fio', 'post', 'rank', 'department', 'organization_name'], 'string', 'max' => 250],
            [['default_organization', 'current_organization'], 'string', 'max' => 5],            
            [['folder_path', 'telephone'], 'string', 'max' => 50],
            [['hash'], 'string', 'max' => 32],
            [['username_windows'], 'unique'],            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'username' => 'Имя пользователя',
            'username_windows' => 'Имя пользователя (windows)',
            'password' => 'Пароль',
            'password1' => 'Пароль',
            'password2' => 'Подтверждение пароля',
            'fio' => 'ФИО',
            'default_organization' => 'Код организации по умолчанию',
            'current_organization' => 'Код организации текущий',
            'role_admin' => 'Администратор',
            'blocked' => 'Заблокировать',
            'folder_path' => 'Каталог пользователя',
            'telephone' => 'Телефон',
            'post' => 'Должность',
            'rank' => 'Чин',
            'about' => 'Описание',
            'department' => 'Отдел',
            'hash' => 'Хэш',
            'organization_name' => 'Наименование организации',
            'roles' => 'Роли',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата изменения',
            'date_delete' => 'Дата удаления',
        ];
    }

    /**
     * Gets query for [[Departments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartments()
    {
        return $this->hasMany(Department::className(), ['author' => 'username_windows']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['author' => 'username_windows']);
    }

    /**
     * Gets query for [[FileDownloads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFileDownloads()
    {
        return $this->hasMany(FileDownload::className(), ['username' => 'username_windows']);
    }

    /**
     * Gets query for [[GroupUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroupUsers()
    {
        return $this->hasMany(GroupUser::className(), ['id_user' => 'id']);
    }

    /**
     * Gets query for [[LogAuthenticates]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogAuthenticates()
    {
        return $this->hasMany(LogAuthenticate::className(), ['username' => 'username_windows']);
    }

    /**
     * Gets query for [[Menus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::className(), ['author' => 'username_windows']);
    }

    /**
     * Gets query for [[Modules]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModules()
    {
        return $this->hasMany(Module::className(), ['author' => 'username_windows']);
    }

    /**
     * Gets query for [[News]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(News::className(), ['author' => 'username_windows']);
    }

    /**
     * Gets query for [[Telephones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTelephones()
    {
        return $this->hasMany(Telephone::className(), ['author' => 'username_windows']);
    }

    /**
     * Gets query for [[Trees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrees()
    {
        return $this->hasMany(Tree::className(), ['author' => 'username_windows']);
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
        $query->andWhere(['default_organization'=>Yii::$app->userInfo->current_organization]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params); // ?
        $query->andFilterWhere(['like', 'username_windows', $this->username_windows]);
        $query->andFilterWhere(['like', 'fio', $this->fio]);

        return $dataProvider;
    }

    /**
     * Gets query for [[UserOrganizations]].
     *
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getOrganizations()
    {
        if (Yii::$app->user->identity->role_admin/*Yii::$app->user->can('admin')*/) {
            $relation = Organization::find();
            $relation->multiple = true;
            return $relation;
        }
        return $this->hasMany(Organization::class, ['code' => 'id_organization'])->viaTable('{{%user_organization}}', ['id_user' => 'id']);
    }
    
    
    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getId() 
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
    
    /**
     * @param type $password
     * @return type
     */
    public function validatePassword($password)
    {
        return \Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }
    
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['username'=>$username]);
    }
    
    /**
     * Finds user by username
     *
     * @param string $username_windows
     * @return static|null
     */
    public static function findByUsernameWindows($username_windows)
    {
        return self::findOne(['username_windows'=>$username_windows]);
    }
    
    
    /**
     * Give username
     * @return string
     */
    public static function getUsername()
    {
        if (!\Yii::$app->user->isGuest && isset(\Yii::$app->user->identity->username)) {
            return \Yii::$app->user->identity->username;
        }
        return self::USER_UNKNOWN_NAME;
    }

    /**
     * {@inheritdoc}
     * @param type $insert
     * @return type
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert) 
    {
        if ($this->password1)  {            
            $this->password = Yii::$app->security->generatePasswordHash($this->password1);
        }
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     * @param type $insert
     * @param type $changedAttributes
     * @throws \Exception
     */
    public function afterSave($insert, $changedAttributes) 
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->tempRoles && is_array($this->tempRoles)) {
            /* @var $auth \yii\rbac\DbManager */
            $auth = \Yii::$app->authManager;
            foreach ($this->tempRoles as $role) {
                $roleObj = $auth->getRole($role);
                $auth->assign($roleObj, $this->id);
            }
        }
    }
    
    /**
     * Все существующие роли
     * @return string[]
     */
    public function getListRoles()
    {
        /* @var $auth \yii\rbac\DbManager */
        $auth = \Yii::$app->authManager;        
        foreach ($auth->getRoles() as $role) {            
            yield $role->name => $role->description;
        }        
    }
    
    /**
     * Подключенные текущему пользователю роли
     * @return string[]
     */
    public function getRoles()
    {
        if ($this->isNewRecord) {
            return [];
        }
        /* @var $auth \yii\rbac\DbManager */
        $auth = \Yii::$app->authManager;
        $roles = $auth->getRolesByUser($this->id);        
        foreach ($roles as $role) {
            yield $role->name => $role->description;
        }
    }

    public function isCan($role)
    {
        /* @var $auth \yii\rbac\DbManager */
        $auth = \Yii::$app->authManager;
        return $auth->checkAccess($this->id, $role);
    }
    
    /**
     * Сохранение ролей пользователя
     * @param string[] $values
     */
    public function setRoles($values)
    {
        $this->removeRolesByUser();
        $this->tempRoles = $values;       
    }
    
    /**
     * Сохранение ролей в БД
     */
    private function removeRolesByUser()
    {
        /* @var $auth \yii\rbac\DbManager */
        $auth = \Yii::$app->authManager;
        $roles = $auth->getRolesByUser($this->id);        
        foreach ($roles as $role) {
            $auth->revoke($role, $this->id);
        }
    }

    /**
     * Изменение текущей организации у текущего пользователя
     * @param string $code код организации
     * @return boolean
     * @throws \yii\db\Exception
     */
    public function changeOrganization($code)
    {
        $username = \Yii::$app->user->identity->username;
        $query = \Yii::$app->db->createCommand()->update('{{%user}}', [
            'current_organization' => $code,
        ], 'username=:username1 or username_windows=:username2', [
            ':username1' => $username,
            ':username2' => $username,
        ]);
        return $query->execute();                
    }


    /** migrate from Yii1 */

    /**
     * Проверка имеет ли доступ пользователь к требуемой орагнизации
     * @param string $organization код организации
     * @return boolean
     * @uses Organization::loadCurrentOrganization()
     * @uses DefaultController::actionChangeCode() (admin)
     */
    public static function checkRightOrganization($organization)
    {
        if ($organization===null) return false;

        if (Yii::$app->user->identity->role_admin) {
            return true;
        }

        $query = new Query();
        return $query->from('{{%user_organization}}')
            ->where([
                'id_user' => Yii::$app->user->id,
                'id_organization' => $organization
            ])->exists();
    }

    /**
     * Учетная запись пользователя с ФИО
     * @return string
     */
    public function getConcat()
    {
        return (!empty($this->username_windows) ? $this->username_windows : '')
            . ' (' . $this->fio . ')';
    }

    /**
     * @return bool
     */
    public function isNewSession()
    {
        /* @var $session \yii\web\Session */
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }

        if (!$session->has('show')) {
            $session->set('show', true);
            return true;
        }
        return false;
    }
    
    /**
     * Является ли пользователь указаной организации
     * @return boolean
     */
    public function isOrg($code)
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        return strpos(Yii::$app->user->identity->username, $code) !== false;
    }

}
