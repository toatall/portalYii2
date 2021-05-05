<?php

use yii\helpers\Url;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */

$this->title = Yii::$app->name;
?>
<div class="site-index">

    <!--div class="row">
        <div class="col-sm-6">
            <div id="container-news-ufns" data-ajax-url="<?= Url::to(['/news/ufns']) ?>"></div>
        </div>
        <div class="col-sm-6">
            <div id="container-news-ifns" data-ajax-url="<?= Url::to(['/news/ifns']) ?>" style="margin-left: 10px;"></div>
        </div>
    </div-->
    <?= Tabs::widget([
        'items' => [
            [
                'label' => 'Новости УФНС',
                'content' => '<div id="container-news-ufns" data-ajax-url="' . Url::to(['/news/ufns']) . '"></div>',
            ],
            [
                'label' => 'Новости ИФНС',
                'content' => '<div id="container-news-ifns" data-ajax-url="' . Url::to(['/news/ifns']) . '"></div>',
            ],
        ],
    ]) ?>
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
    
    runAjaxGetRequest($('#container-news-ufns'));
    runAjaxGetRequest($('#container-news-ifns'));

JS
);
?>