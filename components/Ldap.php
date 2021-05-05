<?php
namespace app\components;

use yii\base\Component;

/**
 * Work with LDAP
 * @author toatall
 */
class Ldap extends Component
{
    /**
     * @var string
     */
    public $ldapServer = 'ldap://servername';
    
    /**
     * @var string
     */
    public $baseDn = 'OU=cn1,DC=example,DC=ru';
    
    /**
     * @var string
     */
    public $bindLogin = 'Admin';
    
    /**
     * @var string
     */
    public $bindPassword = '123';
    
    /**
     * Link for LDAP
     * @var resource 
     */
    protected $ldapConnect;

    /**
     * @var string
     */
    protected $select = '*';

    /**
     * @var string
     */
    protected $filter;

    
    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function init()
    {
        parent::init();
        $this->connectLdap();
    }

    /**
     * Connect to LDAP Server
     * @throws \Exception
     */
    protected function connectLdap()
    {        
        if (!$this->ldapConnect = ldap_connect($this->ldapServer)) {
            throw new \Exception('Error connect! ' . ldap_errno($this->ldapConnect) . ': ' . ldap_error($this->ldapConnect));
        }
        ldap_set_option($this->ldapConnect, LDAP_OPT_PROTOCOL_VERSION, 3);
        
        if (!ldap_bind($this->ldapConnect, $this->bindLogin, $this->bindPassword)) {
            throw new \Exception('Error bind! ' . ldap_errno($this->ldapConnect) . ': ' . ldap_error($this->ldapConnect));
        }
    }

    /**
     * @return resource
     */
    public function getLink()
    {
        return $this->ldapConnect;
    }   

    /**
     * @param $fields
     * @return $this
     */
    public function select($fields)
    {
        $this->select = $fields;
        return $this;
    }

    /**
     * @param $filter
     * @return $this
     */
    public function filter($filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * @return array|mixed|null
     * @throws \Exception
     */
    public function result()
    {
        if (!$ldap_search = ldap_search($this->ldapConnect, $this->baseDn, $this->filter)) {
            throw new \Exception("Ошибка поиска!");
        }
        $res = ldap_get_entries($this->ldapConnect, $ldap_search);

        if (isset($res['count']) && $res['count']>0) {
            $resultSearch = (is_array($res) && count($res) > 1) ? $res[0] : array();
            return $resultSearch;
        }
        return null;
    }

    /**
     * Get information from LDAP by username
     * @param string $username
     * @return array|null
     * @throws \Exception
     */
    public function userInfo($username)
    {
        if (!$username) {
            throw new \Exception('Пользователь не указан!');
        }
        if (!$ldap_search = ldap_search($this->ldapConnect, $this->baseDn, '(sAMAccountName='.$username.')')) {
            throw new \Exception("Пользователь {$username} не найден в {$this->baseDn}!");
        }
        $res = ldap_get_entries($this->ldapConnect, $ldap_search);

        if (isset($res['count']) && $res['count']>0) {
            $resultSearch = (is_array($res) && count($res) > 1) ? $res[0] : array();
            return $resultSearch;
        }
        return null;
    }
    
    
    

    /**
     * Информация из домена по текущему пользователю
     * @return array|bool|null
     * @throws \Exception
     */
    public function currentUserInfo()
    {
        if (\Yii::$app->user->isGuest) {
            return false;
        }
        return $this->userInfo(\Yii::$app->user->identity->username) ?? [];
    }  
    
}
