<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\components\Ldap;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Expression;
use yii\db\Query;

/**
 * Управление пользователями из командной строки:
 * 
 * 1. Обновление информации по всем пользователям из ActiveDirectory
 * php yii user/ldap-update
 * 
 * 2. Обновление информации по отдельному пользователю из ActiveDirectory
 * php yii user/ldap-update --username=8600-90-331 
 * 
 *
 */
class UserController extends Controller
{

    /**
     * Обновление информации по пользователям из Active Directory
     * @param string $user логин пользователя. Если пусто, то выполняется синхронизация по всем пользователям
     * @return int Exit code
     */
    public function actionLdapUpdate($username=null)
    {
        echo "Запуск синхронизации пользователей с ActiveDirectory...\n";
                       
        $query = (new Query())
            ->from('{{%user}}')
            ->filterWhere(['userneme' => $username])
            ->all();
        
        if ($query != null) {
            foreach ($query as $item) {
                echo "Обновление пользователя: {$item['username']} : {$item['fio']}\n";
                $this->ldapUpdate($item['username']);
                
            }       
        }
        else {
            echo "Пользователь {$username} не найден в БД!\n";
        }
                
        echo "Окончание синхронизации пользователей с ActiveDirectory";
        return ExitCode::OK;
    }

    /**
     * Обновление информации о пользователе (таблица `{{%user}}`)
     * Источник обновления - ActiveDirectory (по учетной записи поотзователя)
     * @param string $username логин пользователь
     */
    private function ldapUpdate(string $username)
    {
        /** @var Ldap $ldap */
        $ldap = Yii::$app->ldap;
        $user = $ldap->filter("(sAMAccountName={$username})")->one();
        if ($user) {
            if (Yii::$app->db->createCommand()
                ->update('{{%user}}', [
                    'fio' => $user->asText('cn'),
                    'telephone' => $user->asText('telephonenumber'),
                    'post' => $user->asText('title'),
                    'department' => $user->asText('department'),
                    'organization_name' => $user->asText('company'),
                    'mail_ad' => $user->asText('mail'),
                    'room_name_ad' => $user->asText('physicalDeliveryOfficeName'),
                    'user_disabled_ad' => $this->isDisabledUser($user->asText('userAccountControl')),
                    'description_ad' => $user->asText('description'),
                    'memberof' => implode(', ', $user->asArray('memberOf') ?? []),
                    'date_update_ad' => new Expression('getdate()'),
                ], ['username'=>$username])
                ->execute()) {
                    echo "Пользователь `{$username}` обновлен!\n";
                }
                else {
                    echo "Пользователь `{$username}` не обновлен!\n";
                }            
        }
        else {
            echo "Пользователь `{$username}` не найден!\n";
        }
    }

    /**
     * Проверяется отключена ли учетная запись пользователя в ActiveDirectory
     * Если поле `userAccountControl` содержит флаг ACCOUNTDISABLE = 0x0002 (2), 
     * то ученая запись отключена
     * @return boolean|null
     */
    private function isDisabledUser($field)
    {
        if ($field == null || !is_numeric($field)) {
            return null;
        }
        return ($field & 2) !== 0;
    }
}
