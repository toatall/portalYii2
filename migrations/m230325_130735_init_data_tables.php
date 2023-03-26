<?php

use app\helpers\DateHelper;
use app\models\User;
use yii\db\Migration;

/**
 * Class m230325_130735_init_data_tables
 */
class m230325_130735_init_data_tables extends Migration
{

    /**
     * Текущая дата для сохранения в БД
     * @return string
     */
    private function getDateTime()
    {
        return DateHelper::dateSqlFormat();
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // organization
        $this->insert('{{%organization}}', [
            'code' => '0001',
            'name' => 'Организация 1',
            'date_create' => $this->getDateTime(),
            'date_edit' => $this->getDateTime(),            
        ]);
        $this->insert('{{%organization}}', [
            'code' => '0002',
            'name' => 'Организация 2',
            'date_create' => $this->getDateTime(),
            'date_edit' => $this->getDateTime(),   
            'code_parent' => '0001',         
        ]);

        // user
        ($model = new User([
            'username' => 'admin',
            'password1' => 'P@ssw0rd',
            'password2' => 'P@ssw0rd',
            'username_windows' => 'admin', 
            'fio' => 'Администратор',
            'default_organization' => '0001',
            'current_organization' => '0001',            
        ]))->save();  
        $auth = Yii::$app->authManager;
        $role = $auth->getRole('admin');
        if ($role) {
            $auth->assign($role, $model->id);
        }

        // menu
        $this->insert('{{%menu}}', [
            'id_parent' => 0,
            'type_menu' => 1, 
            'name' => 'Главная',
            'link' => '/',
            'blocked' => 0,
            'author' => 'admin',
            'date_create' => $this->getDateTime(),
            'date_edit' => $this->getDateTime(),
        ]);
        $this->insert('{{%menu}}', [
            'id_parent' => 0,
            'type_menu' => 1, 
            'name' => 'Администрирование',
            'link' => '/admin',
            'blocked' => 0,
            'author' => 'admin',
            'date_create' => $this->getDateTime(),
            'date_edit' => $this->getDateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%menu}}', ['name' => ['Главная', 'Администрирование']]);

        $userModel = User::findOne(['username' => 'admin']);
        if ($userModel !== null) {
            $auth = Yii::$app->authManager;
            $auth->revokeAll($userModel->id);
        }
        $this->delete('{{%user}}', ['username' => 'admin']);

        $this->delete('{{%organization}}', ['code' => ['0001', '0002']]);
    }

   
}
