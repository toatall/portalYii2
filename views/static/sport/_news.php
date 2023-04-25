<?php

/** @var \yii\web\View $this */

use yii\helpers\Url;

?>
<div id="static-sport-news" class="p-2" data-url="<?= Url::to(['/page/index', 'tag'=>'sport']) ?>">></div>

<?php $this->registerJs(<<<JS

    let container = $('#static-sport-news');
    container.html('<div class="fa-2x" style="color: Dodgerblue;"><i class="fas fa-circle-notch fa-spin"></i></div>');    
    $.get(container.data('url'))
    .done(function(data) {
        container.html(data);
    })
    .fail(function(jqXHR) {
        container.html(jqXHR.statusMessage);
    });

JS) ?>