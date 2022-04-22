<?php

use app\assets\EmojiAsset;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var string $hash */
/** @var string $url */


EmojiAsset::register($this);


$idFormComment = 'form-comment-create-' . $hash;
$idPjaxComments = 'pjax-comment-'.$hash;
?>
<div class="comment-index">
    
    <div class="card">
        <div class="card-header">
            <h4>Комментарии</h4>
        </div>
        <div class="card-body">
            <div class="ajax-load" id="container-create" data-url="<?= Url::to(['/comment/create', 'hash'=>$hash, 'url'=>$url, 'container'=>'container-create']) ?>"></div>            
            <div class="ajax-load" id="container-comment-index-<?= $hash ?>" data-url="<?= Url::to(['/comment/comments', 'hash'=>$hash, 'url'=>$url]) ?>"></div>            
        </div>
    </div>

</div>
<?php $this->registerJs(<<<JS

    function ajaxLoad(idContainer) {
        var container = $('#' + idContainer);
        var url = $('#' + idContainer).data('url');        
        
        container.html('<span class="fa-1x"><i class="fas fa-circle-notch fa-spin"></i></span>');

        $.get(url)
        .done(function(data) {
            container.html(data.content);
        })
        .fail(function(jqXHR) {
            container.html('<div class="alert alert-danger">Url: ' + url + '<br /><strong>' + jqXHR.status + ' ' + jqXHR.statusText + '</strong></div>');
        });
    }


    $('.ajax-load').each(function() {
        ajaxLoad($(this).attr('id'));
    });

JS);
?>