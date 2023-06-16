<?php
namespace app\components;

use app\models\History;
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
        if (!Yii::$app->response->isOk) {
            return;
        }
        $url = \yii\helpers\Url::to([$this->route] + $this->actionParams);
        History::save($url, $this->view->title);
    }


    /**
     * Переопределен рендеринг для ajax-запросов
     * {@inheritdoc}
     */
    public function render($view, $params = [], $options = [])
    {
        if (ArrayHelper::getValue($options, 'useParentRender', false) === false) {
            if (Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => $this->titleAjaxResponse,         
                    'content' => $this->renderAjax($view, $params),
                ];
            }
            if (Yii::$app->request->isPjax) {
                return parent::renderAjax($view, $params);
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
