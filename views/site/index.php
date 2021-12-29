<?php

use yii\helpers\Url;
use yii\bootstrap4\Tabs;

/* @var $this yii\web\View */

$this->title = Yii::$app->name;
?>
<div class="site-index">
    <div id="container-news" data-ajax-url="<?= Url::to(['/news/general'])  ?>"></div>   
</div>
<?php
$this->registerJs(<<<JS

    function runAjaxGetRequest(container)
    {
        container.html('<img src="/img/loader_fb.gif" style="height: 100px;">');
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