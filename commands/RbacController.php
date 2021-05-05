<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\commands;

use yii\console\Controller;
use app\models\rules\NewsRule;
use yii\rbac\Role;
use app\models\User;
/**
 * Description of RbacController
 *
 * @author Oleg
 */
class RbacController extends Controller
{
    
    /**
     * Добавление основных ролей и правил
     */
    public function actionInit()
    {        
        $this->deleteAll();        
        /* @var $auth \yii\rbac\DbManager */
        $auth = \Yii::$app->authManager;
        
        $news = $auth->createRole('news');
        $news->description = 'News moderator';
        
        //$newsRule = new NewsRule();
        //$auth->add($newsRule);
                
        //$news->ruleName = $newsRule->name;
        //$auth->add($news);
                       
        $admin = $auth->createRole('admin');
        $admin->description = 'Administrator';
        $auth->add($admin);
                
        $departmentUfns = $auth->createRole('departmentUfns');
        $departmentUfns->description = 'Manage department `Ufns`';
        $auth->add($departmentUfns);

        $telephone = $auth->createRole('telephone');
        $telephone->description = 'Telephones';
        $auth->add($telephone);

        $conference = $auth->createRole('conference');
        $conference->description = ' Meetings, videoconferencing';
        $auth->add($conference);

        $regEcr = $auth->createRole('regEcr');
        $regEcr->description = 'Information about registration in `ECR`';
        $auth->add($regEcr);   
        
        // 
        //$this->createAdmin();
        
    }    
    
    private function createAdmin()
    {
        if (!User::find()->exists()) {
            $model = $this->actionCreateAdmin();
        }
        
        if ($model !== null) {       
            /* @var $auth \yii\rbac\DbManager */
            $auth = \Yii::$app->authManager;
            $roleAdmin = $auth->getRole('admin');
            $auth->assign($roleAdmin, $model->id);
        }
    }
   


    private function deleteAll()
    {        
        $auth = \Yii::$app->authManager;
        
        $auth->removeAllAssignments();
        
        // delete role
        $roles = $auth->getRoles();
        foreach ($roles as $role)
        {
            $auth->remove($role);
        }
                     
        $command = \Yii::$app->db->createCommand();
        $command->delete('{{%auth_rule}}')->execute();
    }  
        
    
    public function actionUpdateRuleNews()
    {
        $auth = \Yii::$app->authManager;
        $newsRule = new NewsRule();        
        $auth->update($newsRule->name, $newsRule);
    }
    
    public function actionCreateAdmin()
    {
        echo "Создание администратора...\n";
        $model = new \app\models\User([
            'username' => 'admin',
            'username_windows' => 'admin',
            'password1' => 'admin',
            'password2' => 'admin',
            'fio' => 'Administrator',
        ]);
        if (!$model->save()) {
            echo "Ошибки:\n";
            print_r($model->getErrors());
            return null;
        }
        echo "Пользователь успешно создан\n";        
        return $model;
    }
    
    public function actionDeleteAdmin()
    {
        User::deleteAll(['username'=>'admin']);
    }
    
    public function actionDeleteAllUser()
    {
        User::deleteAll();
    }
    
    
}
