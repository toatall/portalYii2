<?php

use yii\db\Migration;

/**
 * Class m230325_124832_init_roles
 */
class m230325_124832_init_roles extends Migration
{
    /**
     * @var array
     */
    private $_roles = [
        'admin' => 'Администратор',
    ];


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = \Yii::$app->authManager;
        foreach($this->_roles as $name => $description) {
            if ($auth->getRole($name) === null) {
                $role = $auth->createRole($name);
                $role->description = $description;
                $auth->add($role);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = \Yii::$app->authManager;
        foreach($this->_roles as $name) {
            if (($role = $auth->getRole($name)) !== null) {
                $auth->remove($role);
            }
        }
    }

    
}
