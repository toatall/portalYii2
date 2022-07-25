<?php
namespace app\components;

class Controller extends \yii\web\Controller
{
    
    
    public function afterAction($action, $result) 
    {
        $this->saveLog();
        return parent::afterAction($action, $result);
    }
    
    /**
     * Сохранение в БД
     */
    private function saveLog()
    {                
        $url = \yii\helpers\Url::to([$this->route] + $this->actionParams);
        $query = (new \yii\db\Query())
            ->from('{{%history}}')
            ->where([
                'url' => $url,
            ])
            ->one();
        if ($query != null) {
            // update
            \Yii::$app->db->createCommand()
                ->update('{{%history}}', [
                    'count_visits' => intval($query['count_visits']) + 1,
                ], [
                    'id' => $query['id'],
                ])
                ->execute();
            $this->saveVisit($query['id']);
        }
        else {            
            // insert
            \Yii::$app->db->createCommand()
                ->insert('{{%history}}', [
                    'url' => $url,
                    'count_visits' => 1,
                    'date_create' => time(),
                ])
                ->execute();
                
            $id = \Yii::$app->db->getLastInsertID();
            $this->saveVisit($id);
        }
    }
    
    
    private function saveVisit($idParent)
    {        
        $request = $this->request;
        \Yii::$app->db->createCommand()
            ->insert('{{%history_detail}}', [
                'id_history' => $idParent,
                'is_ajax' => $request->isAjax,
                'is_pjax' => $request->isPjax,
                'method' => $request->method,                
                'date_create' => time(),                
                'author' => \Yii::$app->user->identity->username ?? 'guest',
            ])->execute();
    }
    
}
