<?php


namespace app\components;

use Yii;
use yii\base\Component;

class UserInfo extends Component
{
    /**
     * Массив данных сессии
     * @var array
     */
    private $sessionData;

    /**
     * Префикс для сессии
     * @var string
     */
    public $arrayKey = 'user';
    

    /**
     * {@inheritDoc}
     */
    public function init()
    {
//        var_dump($this);die;
        parent::init();

        /* @var $session \yii\web\Session */
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }
        if (!$session[$this->arrayKey]) {
            $session[$this->arrayKey] = $this->loadUserData();
        }
        $this->sessionData = $session[$this->arrayKey];
    }

    /**
     * Загрузка данных о пользователе
     */
    private function loadUserData()
    {
        $result = [];
        
        // 1. Из базы данных
        $result['db'] = $this->loadUserDataDb(\Yii::$app->user->identity->username);
                
        // 2. Из Active Directory
        // if (\Yii::$app->params['user']['findInAD'] ?? false) {
        //     $result['ad'] = $this->loadUserDataAD(\Yii::$app->user->identity->username);
        // }
        return $result;
    }
    
    
    /**
     * Данные о пользователе из БД
     * @return array|null
     */
    private function loadUserDataDb($username)
    {
        $query = new \yii\db\Query();
        return $query->from('{{%user}}')
              ->where(['username' => $username])
              ->orWhere(['username_windows' => $username])
              ->one();
    }
    
    /**
     * Данные о пользователе из Active Directory
     * @return array|null
     * @todo Нужно реализовать
     */
    private function loadUserDataAD($username)
    {
        /* @var $ldap \app\components\Ldap */
        //$ldap = \Yii::$app->ldap;
        //var_dump($ldap->userInfo($username));die;
        
        return [];
    }

    /**
     * Значение параметра
     * @param string $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->sessionData['ad'][$name] ?? $this->sessionData['db'][$name] ?? null;
    }

    
    /**
     * Данные сессии
     * @return array
     */
    public function getDataSession()
    {
        return $this->sessionData;
    }
    
    /**
     * Очистка сессии
     */
    public function clearSession()
    {
        /* @var $session \yii\web\Session */
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }
        if (isset($session[$this->arrayKey])) {
            unset($session[$this->arrayKey]);
        }
    }

}