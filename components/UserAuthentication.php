<?php
namespace app\components;

use Yii;
use yii\db\Query;

/**
 * 
 */
class UserAuthentication extends \yii\web\User 
{

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->updateLastLogin();              
    }

    /**
     * Запись последнего времени действия пользователя
     */
    private function updateLastLogin()
    {
        /** @var \app\models\User $identity  */
        $identity = $this->identity;
        if ($identity) {                                
            $identity->last_action = \Yii::$app->request->url ?? null;
            $identity->last_action_time = time();
            $identity->save(['last_login', 'last_login_time']);
        }
    }
    

    /**
     * {@inheritdoc}
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        if ($this->checkRight($permissionName)) {
            return true;
        }        

        return parent::can($permissionName, $params, $allowCaching);
    }

    /**
     * Проверка прав текущего пользователя в переданной группе
     * @param string $permissionName наименование группы
     * @return boolean
     */
    private function checkRight($permissionName) 
    {        
        if (Yii::$app->user->isGuest) {
            return false;
        }

        $cache = Yii::$app->cache;
        $userId = Yii::$app->user->id;

        return $cache->getOrSet("$userId-$permissionName", function() use ($permissionName, $userId) {
            return (new Query())
                ->from('{{%group}} t')
                ->leftJoin('{{%group_user}} group_user', 't.id=group_user.id_group')
                ->where([
                    't.name' => $permissionName,
                    'group_user.id_user' => $userId,
                ])
                ->exists();
        }, 600);
        
    }

}