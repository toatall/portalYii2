<?php

namespace tests\unit\models;

use app\models\User;
use app\tests\fixtures\GroupFixture;
use app\tests\fixtures\RoleFixture;
use Faker;
use app\tests\fixtures\UserFixture;
use app\tests\fixtures\OrganizationFixture;
use app\tests\unit\helpers\ReflectionHelper;
use app\tests\unit\helpers\SecurityHelper;
use Yii;
use yii\db\Query;


class UserTest extends \Codeception\Test\Unit
{
    //use \tests\unit\traits\Login;
    
    /**
     * @var Faker\Generator
     */
    private $faker; 
    
    
    public function _fixtures()
    {        
        return [
            RoleFixture::class,            
            UserFixture::class,               
            OrganizationFixture::class, 
            GroupFixture::class, 
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    protected function _before()
    {
        parent::_before();
        $this->faker = Faker\Factory::create();   

        // залогинить пользователя        
        SecurityHelper::login();
        
        // назначение всех ролей                      
        SecurityHelper::assignAllRoles();
    }
    
    
    /**
     * Проверка поиска по ИД
     * @see User::findIddentity()
     */
    public function testFindIdentity()
    {        
        $identity = \Yii::$app->user->identity;
        $user = User::findIdentity($identity->id);
        $this->assertEquals($user, $identity);
        $this->assertNotEquals(User::findIdentity(999), $identity);
    }

    /**
     * Проверка поиска по имени
     * @see User::findByUsername()
     */
    public function testFindByUsername()
    {
        $this->assertEquals(User::findByUsername('admin'), \Yii::$app->user->identity);
        $this->assertNotEquals(User::findByUsername('user'), \Yii::$app->user->identity);
    }

    /**
     * Проверка поиска по имени (windows)
     * @see User::getUsernameWindows()
     */
    public function testFindByUsernameWindows()
    {
        $this->assertEquals(User::findByUsernameWindows('admin'), \Yii::$app->user->identity);
        $this->assertNotEquals(User::findByUsernameWindows('user'), \Yii::$app->user->identity);
    }

    /**
     * Проверка фнукции возвращающая имя пользователя
     * @see User::getUsername()
     */
    public function testGetUsername()
    {
        $this->assertEquals(User::getUsername(), 'admin');
        SecurityHelper::logout();
        SecurityHelper::login('some-login');
        $this->assertEquals(User::getUsername(), User::USER_UNKNOWN_NAME);
    }

    /**
     * Проверка функции возвращающая список ролей текущего пользователя
     */
    public function testRoles()
    {
        /** @var \app\models\User $user */
        $user = \Yii::$app->user->identity;
        $userRoles = iterator_to_array($user->getRoles());
        $this->assertTrue(isset($userRoles['admin']) ?? isset($userRoles['some-role']));
        
        // for new user
        $model = new User();
        $this->assertEquals(iterator_to_array($model->getRoles()), []);
    }

    /**
     * Вспомогательная: текущая организация пользователя
     */
    private function getOrganizationForCurrentUser()
    {
        return (new Query())
            ->from('{{%user}}')
            ->select('current_organization')
            ->where([
                'username' => \Yii::$app->user->identity->username,
            ])
            ->one()['current_organization'];
    }

    /**
     * @see User::changeOrganization($code)
     * Проверка функции изменения организации пользователя
     */
    public function testChangeOrganization()
    {
        /** @var \app\models\User $user */
        $user = \Yii::$app->user->identity;
        $user->changeOrganization('8601');
        $this->assertEquals('8601', $this->getOrganizationForCurrentUser());
        $user->changeOrganization('8602');
        $this->assertEquals('8602', $this->getOrganizationForCurrentUser());
    }

    /**
     * @see User::checkRightOrganization()
     * Проверка функции проверяющий доступ пользователя к указанной организации
     * от роли admin
     */
    public function testCheckRightOrganizationAdmin()
    {
        /** @var \app\models\User $user */
        $user = \Yii::$app->user->identity;

        // если роль admin, то должно всегда быть true, 
        // даже если организации не существует
        SecurityHelper::assignRole(['admin']);
        $this->assertTrue($user->checkRightOrganization('8601'));
        $this->assertTrue($user->checkRightOrganization('jsdfhakjh4736'));
    }

    /**
     * @see User::checkRightOrganization()
     */
    public function testCheckRightOrganizationEmpty()
    {
        /** @var \app\models\User $user */
        $user = \Yii::$app->user->identity;
        $this->assertFalse($user->checkRightOrganization(null));
    }

    /**
     * @see User::checkRightOrganization()
     * Проверка функции проверяющий доступ пользователя к указанной организации
     * от роли some-role
     */
    public function testCheckRightOrganizationSomeRole()
    {
        /** @var \app\models\User $user */
        $user = \Yii::$app->user->identity;                
        SecurityHelper::assignRole(['some-role']);
        $this->assertFalse($user->checkRightOrganization('8600'));
        $this->assertFalse($user->checkRightOrganization('8601'));
    }

    /**
     * @see User::getConcat()
     */
    public function testGetConcat()
    {
        /** @var \app\models\User $user */
        $user = \Yii::$app->user->identity;
        $this->assertEquals('admin (Администратор)', $user->getConcat());
    }   
    
    /**
     * @see User::search($params, $excludeId = null, $excludeIdGroup=null, $excludeRole=null)
     */
    public function testSearchEmpty()
    {
        $model = new User();
    
        // поиск без параметров ()
        $actual = $this->queryAllUsers('id, username', ['default_organization' => \Yii::$app->user->identity->default_organization]);        
        $search = $model->search(null);
        $records = array_map(function($item) {
            return [
                'id' => $item['id'],
                'username' => $item['username'],
            ];
                
        }, $search->getModels());        
        $this->assertEquals($records, $actual);        
    }

    /**
     * Поиск с исключением пользователя с id=2
     * @see User::search()
     */
    public function testSearchExcludeUser()
    {
        $model = new User();
        $actual = $this->queryAllUsers('id, username', ['default_organization' => \Yii::$app->user->identity->default_organization]);        

        $actual2 = array_filter($actual, function($item) {
            if ($item['id'] == 2) {
                return false;
            }
            return true;
        });
        $search = $model->search(null, '2');
        $records = array_map(function($item) {
            return [
                'id' => $item['id'],
                'username' => $item['username'],
            ];
                
        }, $search->getModels());        
        $this->assertEquals(array_values($records), array_values($actual2));
    }

    /**
     * Поиск с исключением группы group1
     * @see User::search()
     */
    public function testSearchExcludeGroup()
    {
        $model = new User();
       
        // добавляем пользователя в группу group1
        $queryGroup = (new Query())
            ->from('{{%group}}')
            ->where(['name' => 'group1'])
            ->one();
        SecurityHelper::addGroup(\Yii::$app->user->id, $queryGroup['id']);

        $search = $model->search(null, null, $queryGroup['id']);
        $records = array_map(function($item) {
            return [
                'id' => $item['id'],
                'username' => $item['username'],
            ];
                
        }, $search->getModels());     
        
        $queryActual = (new Query())
            ->from('{{%user}} u')            
            ->where([
                'u.default_organization' => \Yii::$app->user->identity->default_organization,                
            ])
            ->andWhere('u.id not in (select id_user from {{%group_user}} where id_group=:id_group)', [
                ':id_group' => $queryGroup['id'],
            ])
            ->all();

        $recordsActual = array_map(function($item) {
            return [
                'id' => $item['id'],
                'username' => $item['username'],
            ];
        }, $queryActual);
       
        $this->assertEquals($records, $recordsActual);
    }

    /**
     * Поиск с исключением роли admin
     * @see User::search()
     */
    public function testSearchExcludeRole()
    {   
        $model = new User();
        $search = $model->search(null, null, null, 'admin');
        $records = array_map(function($item) {
            return [
                'id' => $item['id'],
                'username' => $item['username'],
            ];
                
        }, $search->getModels());

        $auth = \Yii::$app->authManager;
        $query = (new Query())
            ->from('{{%user}}')
            ->select('id, username')
            ->where(['not in', 'id', $auth->getUserIdsByRole('admin')])
            ->andWhere(['default_organization' => \Yii::$app->user->identity->default_organization])
            ->all();
        
        $this->assertEquals($records, $query);
    }   


    /**
     * @param string $select 
     * @param array $where
     */
    private function queryAllUsers($select = '*', $where = [])
    {
        return (new Query())
            ->from(User::tableName())
            ->select($select)
            ->andFilterWhere($where)
            ->all();
    }

    /**
     * @see User::getOrganizations()
     */
    public function testOrganizations()
    {
        // for admin
        $model = Yii::$app->user->identity;
        $queryOrganizations = (new Query())
            ->from('{{%organization}}')
            ->select('code')
            ->all();
        $userOrgs = array_map(function($item) {
            return ['code' => $item['code']];
        }, $model->getOrganizations()->all());        
        $this->assertEquals($userOrgs, $queryOrganizations);

        // for user
        SecurityHelper::logout();
        SecurityHelper::login('user');
        $model = Yii::$app->user->identity;
        $queryOrganizations = (new Query())
            ->from('{{%organization}} t')
            ->select('t.code')
            ->leftJoin('{{%user_organization}} us_org', 'us_org.id_organization = t.code')
            ->where([
                'us_org.id_user' => $model->id,
            ])
            ->all();
        $userOrgs = array_map(function($item) {
            return ['code' => $item['code']];
        }, $model->getOrganizations()->all());        
        $this->assertEquals($userOrgs, $queryOrganizations);
    }

    /** 
     * @see User::getListRoles()
     */
    public function testListRoles()
    {
        $rolesActual = [];
        foreach (\Yii::$app->authManager->getRoles() as $item) {
            $rolesActual[$item->name] = $item->description;
        }        
        $roleTest = iterator_to_array(\Yii::$app->user->identity->getListRoles());
        $this->assertEquals($roleTest, $rolesActual);
    }

    /**
     * @see User::setRoles($roles)
     */
    public function testSetRoles()
    {
        $roles = ['role1', 'role2'];
        $auth = \Yii::$app->authManager;

        $user = User::findByUsername('admin');     
        $user->setRoles($roles);
        
        $userRoles = ReflectionHelper::getProperty($user, 'tempRoles');
        $this->assertEquals($roles, $userRoles);
        $this->assertEmpty($auth->getRolesByUser($user->id));    
    }

    /**
     * @see User::getPhotoProfile()
     */
    public function testGetPhotoProfile()
    {
        $user = User::findByUsername('admin');
        $photoEmulate = '/favicon.png';
        $user->photo_file = $photoEmulate;
        $this->assertEquals($user->getPhotoProfile(), $photoEmulate);

        $user->photo_file = null;
        $defaultPhoto = Yii::$app->params['user']['profile']['defaultPhoto'];
        $this->assertEquals($user->getPhotoProfile(), $defaultPhoto);        
    }

    /**
     * @see User::isOrg($code)
     */
    public function testIsOrg()
    {
        $user = User::findByUsername('admin');
        $query = (new Query())
            ->from(User::tableName())
            ->where(['username' => 'admin'])
            ->one();
        $this->assertTrue($user->isOrg($query['default_organization']));

        SecurityHelper::logout();
        $this->assertFalse($user->isOrg($query['default_organization']));
    }

    /**
     * @see User::saveInformation
     */
    public function testSaveInformation()
    {
        // with session
        $user = User::findByUsername('admin');
        $user->saveInformation(800, 600);        
        $sessionId = session_id();
        $query = (new Query())
            ->from('{{%user_log_auth}}')
            ->where([
                'session' => $sessionId,
                'username' => Yii::$app->user->identity->username,
            ])
            ->one();
        $this->assertNotEmpty($query);
        
        // without session
        session_destroy();       
        $user = User::findByUsername('admin');
        $this->assertNull($user->saveInformation(800, 600));
    }   
    

}
