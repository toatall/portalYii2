<?php
namespace tests\unit\traits;

trait TraitLogin 
{
    
    protected function X()
    {
        // подключение организации 8600
        \Yii::$app->db->createCommand()
            ->insert('{{%user_organization}}', [
                'id_user' => \Yii::$app->user->id,
                'id_organization' => '8600',
            ])
            ->execute();
    }

    /**
     * Назначение ролей текущему пользователю
     * @param array $roles
     */
    public function assignRole($roles) 
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
    
}