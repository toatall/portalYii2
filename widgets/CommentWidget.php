<?php
namespace app\widgets;

use yii\base\InvalidConfigException;
use yii\bootstrap4\Html;
use yii\bootstrap4\Widget;
use yii\helpers\Url;

/**
 * @author toatall
 */
class CommentWidget extends Widget
{   
    /**
     * @var string текущая ссылка
     */
    public $url;

    public $borderBottomClass = 'border-top';

    /**
     * @var string заголовок
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

    public $hash;

    /**
     * {@inheritdoc}
     */
    public function init() 
    {        
        parent::init();
        //$this->currentUrl = Url::current();
        
        if ($this->modelName === null) {
            throw new InvalidConfigException("The 'modelName' property is required.");
        }
        if ($this->modelId === null) {
            throw new InvalidConfigException("The 'modelId' property is required.");
        }
    }    

    /**
     * @return string
     */
    private function renderDiv()
    {       
        $url = ($this->url == null) ? Url::current() : $this->url;
        $hash = md5($url);
        $html = Html::beginTag('div', [
            'data-url' =>  Url::to(['/comment/index', 'hash'=>$hash, 'url'=>$url, 'title'=>$this->title, 
                'modelName'=>$this->modelName, 'modelId'=>$this->modelId]),
            'data-comment-url'=>$url, 
            'data-comment-hash'=>$hash,           
            'class' => 'comment-container',
            'id' => 'commnet-container-' . $this->id,
        ]);
        $html .= Html::endTag('div');
        $idContainer = 'commnet-container-' . $this->id;
        $this->getView()->registerJs(<<<JS
            (function() {
                var container = $('#$idContainer');
                var url = $('#$idContainer').data('url');
                var commentUrl = $('#$idContainer').data('comment-url');
                var commentHash = $('#$idContainer').data('comment-hash');
                
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
        return $html;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {        
        return $this->renderDiv();
    }
}
