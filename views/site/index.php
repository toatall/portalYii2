<?php

use yii\helpers\Url;

/** @var yii\web\View $this **/

$this->title = Yii::$app->name;
?>
<div class="site-index">
    <div id="container-news" data-ajax-url="<?= Url::to(['/news/general'])  ?>"></div>
</div>
<?php
$this->registerJs(<<<JS

    function runAjaxGetRequest(container) {
        container.html('<div class="spinner-border text-secondary m-4 fs-1" style="width:3rem;height:3rem;"><span class="visually-hidden">Loading...</span></div>');
        $.get(container.attr('data-ajax-url'))
        .done(function(data) {
            container.html(data);
        })
        .fail(function (jqXHR) {
            container.html('<div class="alert alert-danger">' + jqXHR.status + ' ' + jqXHR.statusText + '</div>');
        });
    }
    
    runAjaxGetRequest($('#container-news'));

JS
);
?>