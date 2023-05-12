<?php
namespace app\tests\unit\helpers;

use app\models\User;
use Yii;
use yii\db\Query;
use yii\rbac\Role;

/**
 * Класс помощник для прохождения процедуры
 * аутентификации и авторизации
 */
class SecurityHelper 
{

    /**
     * @return \yii\rbac\ManagerInterface
     */
    private static function auth()
    {
        return \Yii::$app->authManager;
    }   

    /**
     * Вход под пользователем $username
     * @param string|null $username
     * @return bool
     */
    public static function login($username = 'admin')
    {
        /** @var \app\models\User $user */
        $user = User::find()->where(['username'=>$username])->one();    
        if ($user !== null) {    
            return \Yii::$app->user->login($user);
        }
        return false;
    }

    /**
     * Выход текущего пользователя
     * @return bool
     */
    public static function logout()
    {
        return \Yii::$app->user->logout();
    }

    /**
     * Выход и вход пользователя
     * @param string $username
     */
    public static function relogin($username = 'admin')
    {
        self::logout();
        self::login($username);
    }

    /**
     * Назначение ролей пользователю (Yii::$app->user)
     * @param array $roles
     */
    public static function assignRole($roles) 
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        $auth = self::auth();
        // удаление всех назначенных ролей
        $auth->removeAllAssignments();
        foreach($roles as $roleName) {
            $role = $auth->getRole($roleName);
            $auth->assign($role, \Yii::$app->user->getId());
        }
    }

    /**
     * Назначить все доступные роли текущему пользователю
     */
    public static function assignAllRoles()
    {
        $roles = array_map(function($item) {
            /** @var Role $item */
            return $item->name;
        } ,self::auth()->getRoles());
        self::assignRole($roles);
    }

    /**
     * Отозвать все разрешения у текущего пользователя
     */
    public static function revokeRoles()
    {
        self::auth()->removeAllAssignments();
    }

    /**
     * Добавить пользователя в группу
     * @param int $userId
     * @param int $groupId
     */
    public static function addGroup($userId, $groupId)
    {
        if (!(new Query())
            ->from('{{%group_user}}')
            ->where([
                'id_group' => $groupId,
                'id_user' => $userId,
            ])
            ->exists()) {
            Yii::$app->db->createCommand()
                ->insert('{{%group_user}}', [
                    'id_group' => $groupId,
                    'id_user' => $userId,                   
                ])
                ->execute();
        }
    }

    public static function createRole($roleName, $includeCurrentUser=false)
    {
        $auth = Yii::$app->authManager;
        if (($role = $auth->getRole($roleName)) == null) {
            $role = $auth->createRole($roleName);
            $auth->add($role);
        }
       
        if ($includeCurrentUser) {
            $auth->assign($role, Yii::$app->user->id);
        }        
    }
    
}