<?php
namespace app\modules\admin\modules\grantaccess\models;

use Yii;
use yii\base\Component;
use yii\db\Query;

/**
 * @author toatall
 */
class GrantAccess extends Component
{

    /**
     * @var int
     */
    public $cacheDurationAdGroup = 60 * 60  * 24;

    /**
     * @var int
     */
    public $cacheDurationUsers = 60 * 60  * 24;

    /**
     * @param string $permissionName
     * @return bool
     */
    public function can(string $permissionName)
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        
        return $this->userInGroup($permissionName) 
            || $this->userMembersInGroup($permissionName);
    }
   

    /**
     * Очистка кэша по имени группы
     * @param string $groupName
     * @return bool
     */
    public function clearAllCache($groupName)
    {
        return $this->clearAdGroupCache($groupName) 
            && $this->clearUsersCache($groupName);
    }

    /**
     * Очистка кэша по ActiveDirectory группам
     * @param string $groupName
     * @return bool
     */
    public function clearAdGroupCache($groupName)
    {
        return Yii::$app->cache->delete("$groupName-adgroup");
    }

    /**
     * Очистка кэша по пользователям
     * @param string $groupName
     * @return bool
     */
    public function clearUsersCache($groupName)
    {
        return Yii::$app->cache->delete("$groupName-users");
    }

    /**
     * @param string $groupName
     * @return bool
     */
    protected function userInGroup(string $groupName)
    {
        $cache = Yii::$app->cache;
        $userId = Yii::$app->user->id ?? 0;
        
        $result = $cache->get("$groupName-users")[$userId] ?? null;
        if ($result === null) {
            $result = $this->checkUserInGroup($groupName, $userId);
            
            if (($old = $cache->get("$groupName-users")) === false) {
                $old = [];
            }
            $cache->set("$groupName-users", ((array)$old + [$userId => $result]), $this->cacheDurationUsers);
        }
        return $result === true;
    }

    /**
     * @param string $groupName
     * @param int $userId
     * @return bool
     */
    protected function checkUserInGroup(string $groupName, int $userId)
    {
        return (new Query())
                ->from('{{%grant_access_group}} gr')
                ->leftJoin('{{%grant_access_group__user}} group_user', 'gr.id = group_user.id_group')
                ->where([
                    'gr.unique' => $groupName,
                    'group_user.id_user' => $userId,
                ])
                ->andWhere('group_user.date_end IS NULL OR group_user.date_end > :date', [':date' => time()])
                ->exists();
    }

    /**
     * @param string $groupName
     * @return bool
     */
    protected function userMembersInGroup(string $groupName)
    {
        $cache = Yii::$app->cache;
        $userId = Yii::$app->user->id;        
        
        $result = $cache->get("$groupName-adgroup")[$userId] ?? null;                
        if ($result === null) {  
            $result = $this->checkUserMembersInGroup($groupName, $userId);           

            if (($old = $cache->get("$groupName-adgroup")) == false) {
                $old = [];
            }
            $cache->set("$groupName-adgroup", \yii\helpers\ArrayHelper::merge((array)$old, [$userId => $result]), $this->cacheDurationAdGroup);
        }
        return $result === true;
    }

    /**
     * @param string $groupName
     * @param int $userId
     * @return bool
     */
    protected function checkUserMembersInGroup(string $groupName, int $userId)
    {
        return (new Query())
                ->from('{{%user}} usr')
                ->innerJoin('{{%grant_access_group__adgroup}} gr_adgroup', "{{usr.memberof}} like '%' + {{gr_adgroup.group_name}} + '%'")
                ->innerJoin('{{%grant_access_group}} gr_group', 'gr_group.id = gr_adgroup.id_group')                
                ->where([
                    'gr_group.unique' => $groupName,
                    'usr.id' => $userId,                    
                ])
                ->andWhere('gr_adgroup.date_end IS NULL OR gr_adgroup.date_end > :date', [':date' => time()])
                ->exists();
    }

    

}