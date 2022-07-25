<?php
namespace app\components;

class Controller extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function afterAction($action, $result) 
    {
        $this->saveLog();
        return parent::afterAction($action, $result);
    }
    
    /**
     * Сохранение в БД адреса и количества просмотров
     */
    private function saveLog()
    {                
        if (!\Yii::$app->response->isOk) {
            return;
        }
        $date = date('Y-m-d');
        $url = \yii\helpers\Url::to([$this->route] + $this->actionParams);
        $query = (new \yii\db\Query())
            ->from('{{%history}}')
            ->where([
                'url' => $url, 
                'date' => $date,
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
                    'date' => $date,
                    'title' => $this->view->title,
                ])
                ->execute();
                
            $id = \Yii::$app->db->getLastInsertID();
            $this->saveVisit($id);
        }
    }
    
    /**
     * @param int $idParent
     */
    private function saveVisit($idParent)
    {        
        $request = $this->request;
           
        \Yii::$app->db->createCommand()
            ->insert('{{%history_detail}}', [
                'id_history' => $idParent,
                'is_ajax' => $request->isAjax,
                'is_pjax' => $request->isPjax,
                'method' => $request->method, 
                'host' => $request->userHost,
                'ip' => $request->userIP,
                'date_create' => time(),                
                'author' => \Yii::$app->user->identity->username ?? 'guest',
            ])->execute();
    }
    
}
