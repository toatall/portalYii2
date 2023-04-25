<?php
namespace app\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class Controller extends \yii\web\Controller
{

    /**
     * Заголовок для ajax-запроса
     * @var string
     */
    public $titleAjaxResponse = null;

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
                'author_org_code' => \Yii::$app->user->identity->default_organization ?? null,
            ])->execute();
    }

    /**
     * Переопределен рендеринг для ajax-запросов
     * {@inheritdoc}
     */
    public function render($view, $params = [], $options = [])
    {
        if (ArrayHelper::getValue($options, 'useParentRender', false) === false) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => $this->titleAjaxResponse,         
                    'content' => $this->renderAjax($view, $params),
                ];
            }
        }
        return parent::render($view, $params);
    }

    /**
     * Переопределена переадресация для ajax-запросов
     * {@inheritdoc}
     */
    public function redirect($url, $statusCode = 302, $options = [])
    {
        if (ArrayHelper::getValue($options, 'useParentRender', false) === false) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return 'OK';
            }
        }
        return parent::redirect($url, $statusCode);
    }
    

}
