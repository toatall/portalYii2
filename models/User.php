<?php

namespace app\models;

use app\models\department\Department;
use app\models\menu\Menu;
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
 * @property int $blocked
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
 * @property string $mail_ad
 * @property string $room_name_ad
 * @property string $user_disabled_ad
 * @property string $user_status_ad
 * @property string $date_update_ad
 * @property string $description_ad
 * @property string $photo_file
 * @property string $last_action
 * @property int $last_action_time
 * 
 * @property string $concat
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
     * @codeCoverageIgnore
     */
    public function rules()
    {
        return [
            [['username_windows'], 'required'],
            [['blocked'], 'integer'],
            [['about'], 'string'],
            [['password1', 'password2', 'description_ad'], 'string'],            
            ['password1', 'compare', 'compareAttribute' => 'password2'],
            [['date_create', 'date_edit', 'date_delete', 'roles', 'memberof', 'date_update_ad'], 'safe'],
            [['username', 'username_windows', 'fio', 'post', 'rank', 'department', 'organization_name', 'mail_ad'], 'string', 'max' => 250],
            [['default_organization', 'current_organization'], 'string', 'max' => 5],            
            [['telephone', 'room_name_ad'], 'string', 'max' => 50],
            [['photo_file'], 'string', 'max' => 500],
            [['hash'], 'string', 'max' => 32],
            [['username_windows'], 'unique'],            
            [['user_disabled_ad'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
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
            'blocked' => 'Заблокировать',            
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
            'last_login' => 'Дата последнего входа',
            'mail_ad' => 'Email',
            'room_name_ad' => 'Кабинет',
            'description_ad' => 'Описание',            
        ];
    }

    /**
     * Gets query for [[Departments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartments()
    {
        return $this->hasMany(Department::class, ['author' => 'username_windows']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['author' => 'username_windows']);
    }

    
    /**
     * Gets query for [[Menus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(Menu::class, ['author' => 'username_windows']);
    }

    /**
     * Gets query for [[Modules]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModules()
    {
        return $this->hasMany(Module::class, ['author' => 'username_windows']);
    }
    
    /**
     * Gets query for [[Trees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrees()
    {
        return $this->hasMany(Tree::class, ['author' => 'username_windows']);
    }

    /**
     * Поиск
     * @param array $params
     * @param string|null $excludeId идентификаторы пользователей (1,2,3) для исключения их
     * @param int|null $excludeIdGroup идентификатор группы, для исключения пользователей состоящих в этой группе
     * @param string|null $excludeRole наименование роли для исключения пользователей входящих в эту группу
     * @return ActiveDataProvider
     */
    public function search($params, $excludeId = null, $excludeIdGroup=null, $excludeRole=null)
    {
        $query = self::find()->alias('t');
        if ($excludeId) {
            $query->andWhere(['not in', 't.id', explode(',', $excludeId)]);
        }
        $query->andWhere(['t.default_organization'=>Yii::$app->userInfo->current_organization]);

        // если указан идентификатор группы, то исключить этих пользователей из результата
        if (is_numeric($excludeIdGroup)) {
            $query->andWhere('t.id not in (select id_user from {{%group_user}} where id_group=:id_group)', [':id_group'=>$excludeIdGroup]);           
        }

        if ($excludeRole) {
            $ids = Yii::$app->authManager->getUserIdsByRole($excludeRole);
            if ($ids) {
                $query->andWhere(['not in', 't.id', $ids]);
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params); 

        if (Yii::$app->user->can('admin')) {
            $query->andFilterWhere(['like', 't.current_organization', $this->current_organization]);
        }
        $query->andFilterWhere(['like', 't.department', $this->department]);
        $query->andFilterWhere(['like', 't.username', $this->username]);
        $query->andFilterWhere(['like', 't.username_windows', $this->username_windows]);
        $query->andFilterWhere(['like', 't.fio', $this->fio]);

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
        if (Yii::$app->user->can('admin')) {
            $relation = Organization::find();
            $relation->multiple = true;
            return $relation;
        }
        return $this->hasMany(Organization::class, ['code' => 'id_organization'])
            ->viaTable('{{%user_organization}}', ['id_user' => 'id']);
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
     * @codeCoverageIgnore
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
    
    /**
     * @param string $password
     * @return boolean
     * @codeCoverageIgnore
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
     * @return mixed
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
     * @return mixed
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
     * Отзыв всех ролей пользователя
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

    /**
     * @return string ссылка на фотографию пользователя
     * Если фотографии нет, то показывается фото указанное 
     * в params `user.profile.defaultPhoto`
     */
    public function getPhotoProfile()
    {
        $path = Yii::$app->basePath . DIRECTORY_SEPARATOR . 'web';
        if (!empty($this->photo_file) && file_exists($path . $this->photo_file)) {
            return $this->photo_file;
        }
        return Yii::$app->params['user']['profile']['defaultPhoto'];
    }

   
    /**
     * Проверка имеет ли доступ пользователь к требуемой орагнизации
     * @param string $organization код организации
     * @return boolean
     * @uses Organization::loadCurrentOrganization()
     * @uses DefaultController::actionChangeCode() (admin)
     */
    public static function checkRightOrganization($organization)
    {
        if (empty($organization)) {
            return false;
        }

        if (Yii::$app->user->can('admin')) {
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
     * Является ли пользователь указаной организации
     * @return boolean
     */
    public function isOrg($code)
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        return \Yii::$app->user->identity->default_organization == $code;
    }

    /**
     * @param int $width
     * @param int $height
     */
    public function saveInformation($width, $height)
    {
        if (($session = session_id()) === false) {
            return;
        }
        $browser = [];
        if (ini_get('browscap')) {
            $browser = get_browser(null, true);
        }       

        $sql = "
            if not exists(select 1 from {{%user_log_auth}} where session=:session and username=:username)
            begin
                insert into {{%user_log_auth}} (
                     [[session]]           
                    ,[[browser_name]]
                    ,[[browser_ver]]
                    ,[[browser_maker]]
                    ,[[platform]]
                    ,[[platform_description]]
                    ,[[platform_maker]]
                    ,[[platform_bits]]
                    ,[[screen_width]]
                    ,[[screen_height]]
                    ,[[agent_string]]
                    ,[[username]]
                    ,[[date_create]]
                )
                values (
                     :session2
                    ,:browser_name
                    ,:browser_ver
                    ,:browser_maker
                    ,:platform
                    ,:platform_description
                    ,:platform_maker
                    ,:platform_bits
                    ,:screen_width
                    ,:screen_height
                    ,:agent_string
                    ,:username2
                    ,:date_create
                )
            end        
        ";
        \Yii::$app->db->createCommand($sql, [
            ':session' => $session,
            ':session2' => $session,
            ':browser_name' => $browser['browser'] ?? null,
            ':browser_ver' => $browser['version'] ?? null,
            ':browser_maker' => $browser['browser_maker'] ?? null,
            ':platform' => $browser['platform'] ?? null,
            ':platform_description' => $browser['platform_description'] ?? null,
            ':platform_maker' => $browser['platform_maker'] ?? null,
            ':platform_bits' => $browser['platform_bits'] ?? null,
            ':screen_width' => $width,
            ':screen_height' => $height,
            ':agent_string' => \Yii::$app->request->userAgent ?? null,
            ':username' => $this->username,
            ':username2' => $this->username,
            ':date_create' => time(),
        ])->execute();
    }

}
