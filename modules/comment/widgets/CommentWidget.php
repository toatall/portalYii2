<?php
namespace app\modules\comment\widgets;

use yii\base\InvalidConfigException;
use yii\bootstrap5\Html;
use yii\bootstrap5\Widget;
use yii\helpers\Url;

/**
 * Виджет добавляющий комментарии к странице
 * Обязательно требуется указать поле $modelName и $modelId 
 * для привязки комментариев к странице 
 * 
 * Пример использования:
 *      CommentWidget::widget([
 *          'modelName' => 'News',
 *          'modelId' => $model->id,
 *      ]);
 * 
 * 
 * @author toatall
 */
class CommentWidget extends Widget
{   
    /**
     * ссылка как идентификатор
     * @var string 
     */
    public $url;
    
    /**
     * @var string
     */
    public $borderBottomClass = 'border-top';

    /**
     * заголовок
     * @var string 
     */
    public $title = 'Комментарии';

    /**
     * @var string
     */
    public $modelName;

    /**
     * @var integer
     */
    public $modelId;

   
    /**
     * {@inheritdoc}
     */
    public function init() 
    {        
        parent::init();
        
        if ($this->modelName === null) {
            throw new InvalidConfigException("The 'modelName' property is required.");
        }
        if ($this->modelId === null) {
            throw new InvalidConfigException("The 'modelId' property is required.");
        }
    }    
    
    /**
     * {@inheritdoc}
     */
    public function run()
    {        
        return $this->renderDiv();
    }

    /**
     * @return string
     */
    private function renderDiv()
    {       
        $url = ($this->url == null) ? Url::current() : $this->url;
        $hash = md5($url);
        $html = Html::beginTag('div', [
            'data-url' =>  Url::to(['/comment/index', 'hash' => $hash, 'url' => $url, 'title' => $this->title, 
                'modelName' => $this->modelName, 'modelId' => $this->modelId]),
            'data-comment-url' => $url,
            'data-comment-hash' => $hash,
            'class' => 'comment-container',
            'id' => 'commnet-container-' . $this->id,
        ]);
        $html .= Html::endTag('div');
        $this->registerJs('commnet-container-' . $this->id);
        return $html;
    }
    
    /**
     * Регистрация js
     * @param string $idElement
     */
    protected function registerJs($idElement)
    {
        $view = $this->getView();
        $view->registerJs(<<<JS
            (function() {
                let container = $('#$idElement');
                let url = $('#$idElement').data('url');
                let commentUrl = $('#$idElement').data('comment-url');
                let commentHash = $('#$idElement').data('comment-hash');
                
                container.html('<span class="fa-1x"><i class="fas fa-circle-notch fa-spin"></i> Загрузка...</span>');

                $.get(url)
                .done(function(data) {
                    container.html(data);                   
                })
                .fail(function(jqXHR) {
                    container.html('<div class="alert alert-danger">Url: ' + url + '<br /><strong>' + jqXHR.status + ' ' + jqXHR.statusText + '</strong></div>');
                });
            })(); 
        JS);
    }

    
}
