<?php

namespace tests\unit\models;

use app\models\User;
use app\tests\fixtures\RoleFixture;
use Faker;
use app\tests\fixtures\UserFixture;
use app\tests\fixtures\OrganizationFixture;
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
            'roles' => [
                'class' => RoleFixture::class,
            ],
            'users' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'login_data.php',
            ],
            'organizations' => [
                'class' => OrganizationFixture::class,
                'dataFile' => codecept_data_dir() . 'organization_data.php',
            ],
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
        $user = User::find()->where(['username'=>'admin'])->one();        
        \Yii::$app->user->login($user);
        
        // по-умолчанию назначение всех ролей     
        $this->assignRole(['admin', 'some-role']);
        
        //$this->X();
    }

    /**
     * Назначение ролей текущему пользователю
     * @param array $roles
     */
    private function assignRole($roles) 
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        $auth = \Yii::$app->authManager;
        // удаление всех назначенных ролей
        $auth->removeAllAssignments();
        foreach($roles as $roleName) {
            $role = $auth->getRole($roleName);
            $auth->assign($role, \Yii::$app->user->getId());
        }        
    }


    /**
     * Проверка, что пользователь не гость
     */
    public function testIsGuest()
    {      
        expect_not(\Yii::$app->user->isGuest);
    }

    /**
     * Проверка прав у текущего пользователя
     */
    public function testInRoleAdmin()
    {
        expect_that(\Yii::$app->user->can('admin'));
        expect_that(\Yii::$app->user->can('some-role'));

        expect_not(\Yii::$app->user->can('user')); 
    }
    
    /**
     * Проверка поиска по ИД
     * @see User::findIddentity()
     */
    public function testFindIdentity()
    {
        $this->assertEquals(User::findIdentity(\Yii::$app->user->getId()), \Yii::$app->user->identity);
        $this->assertNotEquals(User::findIdentity(11), \Yii::$app->user->identity);
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
    }

    /**
     * Проверка функции возвращающая список ролей текущего пользователя
     */
    public function testRoles()
    {
        /** @var \app\models\User $user */
        $user = \Yii::$app->user->identity;
        $this->assertEquals(iterator_to_array($user->getRoles()), ['admin' => null, 'some-role' => null]);
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
        $this->assignRole('admin');
        expect_that($user->checkRightOrganization('8601'));
        expect_that($user->checkRightOrganization('jsdfhakjh4736'));
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
        $this->assignRole('some-role');
                
        expect_that($user->checkRightOrganization('8600'));
        expect_not($user->checkRightOrganization('8601'));               
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

}
