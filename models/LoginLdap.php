<?php


namespace app\models;

use Yii;
use yii\base\Exception;


class LoginLdap
{
    /**
     * @param string|null $username
     * @return bool
     * @throws Exception
     */
    public function login(string $username = null)
    {
        if ($username == null) {
            $username = $this->parseLogin();
        }

        $user = $this->getUser($username);
        if ($user == null) {
            //throw new Exception('Произошла ошибка аутентефикации! Переменная $user=null!');
            return false;
        }
        return Yii::$app->user->login($user);
    }

    /**
     * @return array
     * @throws Exception
     */
    private function parseLogin()
    {
        $username = $this->extractLogin();
        return $this->removeLoginDomain($username);
    }

    /**
     * Удаление доменной части
     * @param string $fullUsername
     * @return array
     */
    private function removeLoginDomain(string $fullUsername)
    {
        $user = explode('\\', $fullUsername);
        if (isset($user[1])) {
            return $user[1];
        }
        return $user;
    }

    /**
     * Извлечение имени пользователя из глобального массива $_SERVER
     * @return string
     * @throws Exception
     */
    private function extractLogin()
    {
        $loginName = null;
        foreach (['AUTH_USER', 'LOGON_USER', 'REMOTE_USER'] as $serverParam) {
            if (isset($_SERVER[$serverParam]) && !empty($_SERVER[$serverParam])) {
                return $_SERVER[$serverParam];
            }
        }

        throw new Exception('Отсутсвует одна из переменных: AUTH_USER, LOGON_USER, REMOTE_USER в массиве $_SERVER. Проверьте настройки Windows-аутентификации.');
    }

    /**
     * Поиск/создание пользователя в таблице User
     * @param string $username
     * @return User
     * @throws \Exception
     */
    private function getUser(string $username)
    {
        $modelUser = User::findByUsername($username);
        if ($modelUser === null) {
            $modelUser = new User();
            $modelUser->username = $username;
            $modelUser->username_windows = $modelUser->username;
            $modelUser->current_organization = $this->getOrgCodeByLogin($username);
            $modelUser->authKey = md5($modelUser->username);
            $modelUser->password = $modelUser->authKey;            

            // Если пользователь этот первый и у него нет прав, то назначаю права админа
            if (User::find()->count() == 1) {
                $this->createAdmin($modelUser->id);
            }
        }
        if ($modelUser !== null) {
            $modelUser->last_login = Yii::$app->formatter->asDatetime('now');              
        }
        $this->getLdapUserData($modelUser);
        $modelUser->save(false);
        
        return $modelUser;
    }
    
    /**
     * Получение информации из ActiveDirectory
     * @param User $user
     */
    protected function getLdapUserData(User $user)
    {
        /* @var $ldap \app\components\Ldap */
        $ldap = \Yii::$app->ldap;

        /** @var \app\components\LdapResult $ldapData */
        $ldapData = $ldap->userInfo($user->username);
        
        $user->fio = $ldapData->asText('cn');
        if ($user->default_organization == null) {
            $user->default_organization = $user->current_organization;
        }
        $user->telephone = $ldapData->asText('telephonenumber');
        $user->post = $ldapData->asText('title');
        $user->department = $ldapData->asText('department');
        if (is_array($members = $ldapData->asArray('memberof'))) {
            $user->memberof = implode(', ', $members); 
        }
               
    }
    
    /**
     * 
     * @param array $data
     * @param string $attribute
     * @return string|null
     */
    protected function getString($data, $attribute)
    {
        return $data[$attribute][0] ?? null;
    }
    
    /**
     * 
     * @param array $data
     * @param string $attribute
     * @return array
     */
    protected function getGroups($data, $attribute)
    {        
        return $this->parseGroups($data[$attribute] ?? []);
    }
    
    /**
     * Выполняется преобразование имен групп из канонического вида в обычное наименование
     * @param $groupAd
     * @return array|bool
     */
    protected function parseGroups($groupAd)
    {
        $result = [];
        foreach ($groupAd as $item) {
            $mathes = [];
            preg_match('/^CN=([^,]*),/', $item, $mathes);
            if (isset($mathes[1])) {
                $result[] = $mathes[1];
            }
        }
        return $result;
    }
    

    /**
     * Делаем пользователя админом
     * @param $id
     * @throws \Exception
     */
    private function createAdmin($id)
    {
        $auth = Yii::$app->authManager;
        $roleAdmin = $auth->getRole('admin');
        if ($roleAdmin) {
            $auth->assign($roleAdmin, $id);
        }
    }

    /**
     * Извлечение кода организации из имени логина
     * Формат логина должен быть:
     * 9999-99-999 или n9999-99-999
     * @param string $username
     * @return mixed|null
     */
    private function getOrgCodeByLogin(string $username)
    {
        $result = null;
        if (preg_match('/^\d{4}|^n\d{4}/', $username, $result)) {
            if (is_array($result) && count($result)>0) {
                return $result[0];
            }
        }
        return null;
    }


}