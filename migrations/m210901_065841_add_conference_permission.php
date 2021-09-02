<?php

use yii\db\Migration;

/**
 * Class m210901_065841_add_conference_permission
 */
class m210901_065841_add_conference_permission extends Migration
{

    /**
     * @var yii\rbac\ManagerInterface
     */
    private $auth;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->auth = Yii::$app->authManager;
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {               
        $permConferenceApprove = $this->auth->createPermission('permConferenceApprove');
        $permConferenceApprove->description = 'Согласование собраний, ВКС';
        $this->auth->add($permConferenceApprove);  
        
        $adminRole = $this->auth->getRole('admin');
        $this->auth->addChild($adminRole, $permConferenceApprove);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (($perm = $this->auth->getPermission('permConferenceApprove')) !== null) {
            $this->auth->remove($perm);
        }
    }

}
