<?php
namespace app\components;

use app\models\LoginLdap;
use Yii;
use yii\db\Query;
use yii\web\ForbiddenHttpException;

/**
 * @author toatall
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
        if ($this->id) {
            Yii::$app->db->createCommand()
                ->update('{{%user}}', [
                    'last_action' => Yii::$app->request->url ?? null,
                    'last_action_time' => time(),                
                ], [
                    'id' => $this->id,
                ])->execute();            
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {        
        if (Yii::$app->grantAccess->can($permissionName)) {
            return true;
        }       

        if ($this->checkRight($permissionName)) {
            return true;
        }        

        return parent::can($permissionName, $params, $allowCaching);
    }

    /**
     * Проверка прав текущего пользователя в переданной группе
     * @param string $permissionName наименование группы
     * @return boolean
     * @deprecated version
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
        }, 60 * 60 * 4);
        
    }

    /**
     * Переопределение входа пользователя, если включена windows-аутентификация
     * {@inheritdoc}
     */
    public function loginRequired($checkAjax = true, $checkAcceptHeader = true)
    {
        if (!Yii::$app->params['user']['useWindowsAuthenticate'] ?? false) {
            return parent::loginRequired($checkAjax, $checkAcceptHeader);
        }

        $loginName = $_SERVER['LOGON_USER'] ?? null;
        if (empty($loginName)) {
            throw new \Exception('Параметр $_SERVER[\'LOGON_USER\'] отсутствует или пустой. Убедитесь, что windows-аутентефикацию включена в настройках веб-сервера!');
        }
        
        if (!(new LoginLdap())->login($this->removeDomainInUsernmae($loginName))) {
            throw new ForbiddenHttpException(Yii::t('yii', 'Login Required'));     
        }

        $request = Yii::$app->getRequest();
        $canRedirect = !$checkAcceptHeader || $this->checkRedirectAcceptable();
        if ($this->enableSession
            && $request->getIsGet()
            && (!$checkAjax || !$request->getIsAjax())
            && $canRedirect
        ) {
            $this->setReturnUrl($request->getAbsoluteUrl());
        }
        if ($this->loginUrl !== null && $canRedirect) {
            $loginUrl = (array) $this->loginUrl;
            if ($loginUrl[0] !== Yii::$app->requestedRoute) {
                return Yii::$app->getResponse()->redirect(['/site/save-user-agent-info']);
            }
        }
        
        throw new ForbiddenHttpException(Yii::t('yii', 'Login Required'));       
    }

    /**
     * Удаление домена из имени пользователя
     * @param string $fullName
     * @return string
     */
    protected function removeDomainInUsernmae($fullName)
    {        
        $parts = preg_split("/\\\/", $fullName);
        return $parts[1] ?? $parts[0];
    }


}