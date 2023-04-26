<?php

namespace tests\unit\models;


use app\tests\fixtures\RoleFixture;
use app\tests\fixtures\UserFixture;
use app\tests\fixtures\OrganizationFixture;
use app\tests\unit\helpers\SecurityHelper;


class SecurityTest extends \Codeception\Test\Unit
{   
    /**
     * {@inheritdoc}
     */
    public function _fixtures()
    {        
        return [
            RoleFixture::class,            
            UserFixture::class,               
            OrganizationFixture::class,           
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    protected function _before()
    {
        parent::_before();        

        // залогинить пользователя        
        SecurityHelper::login();
        
        // назначение всех ролей                      
        SecurityHelper::assignAllRoles();
    }

    /**
     * User is not a guest
     */
    public function testIsGuest()
    {      
        $this->assertFalse(\Yii::$app->user->isGuest);
    }

    /**
     * Check user roles
     */
    public function testAreRoles()
    {
        $this->assertTrue(\Yii::$app->user->can('admin'));
        $this->assertTrue(\Yii::$app->user->can('some-role'));
        $this->assertFalse(\Yii::$app->user->can('user')); 
    }   


}
