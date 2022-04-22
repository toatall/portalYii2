<?php
namespace app\widgets;

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
    private $currentUrl;

    public $borderBottomClass = 'border-top';

    /**
     * {@inheritdoc}
     */
    public function init() 
    {        
        parent::init();
        $this->currentUrl = Url::current();        
    }    

    /**
     * @return string
     */
    private function renderDiv()
    {
        $hash = md5($this->currentUrl);
        $html = Html::beginTag('div', [
            'data-url' =>  Url::to(['/comment/index', 'hash'=>$hash, 'url'=>$this->currentUrl]),
            'data-comment-url'=>$this->currentUrl, 
            'data-comment-hash'=>$hash,
            'class' => 'comment-container mt-5',
            'id' => 'commnet-container-' . $this->id,
        ]);
        $html .= Html::endTag('div');
        $idContainer = 'commnet-container-' . $this->id;
        $this->getView()->registerJs(<<<JS
            
                var container = $('#$idContainer');
                var url = $('#$idContainer').data('url');
                var commentUrl = $('#$idContainer').data('comment-url');
                var commentHash = $('#$idContainer').data('comment-hash');
                
                container.html('<span class="fa-1x"><i class="fas fa-circle-notch fa-spin"></i> Загрузка комментариев...</span>');

                $.get(url)
                .done(function(data) {
                    container.html(data);
                })
                .fail(function(jqXHR) {
                    container.html('<div class="alert alert-danger">Url: ' + url + '<br /><strong>' + jqXHR.status + ' ' + jqXHR.statusText + '</strong></div>');
                });
            
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
