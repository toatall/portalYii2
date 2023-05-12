<?php

namespace app\tests\fixtures;

use Yii;
use yii\test\Fixture;

class RoleFixture extends Fixture
{
    /**
     * Создание ролей
     */
    public function load()
    {        
        $rbac = Yii::$app->authManager;
        if (!$rbac->getRole('admin')) {
            $roleAdmin = $rbac->createRole('admin');
            $rbac->add($roleAdmin);
        }
        if (!$rbac->getRole('some-role')) {
            $roleSome = $rbac->createRole('some-role');
            $rbac->add($roleSome);
        }
    }

    /**
     * Удаление ролей
     */
    public function unload()
    {
        $rbac = \Yii::$app->authManager;        
        $rbac->removeAllAssignments();
    }
} 