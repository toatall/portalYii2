<?php
/** @var \yii\web\View $this */
/** @var \app\models\thirty\ThirtyRadio $model */

use yii\bootstrap4\Tabs;
use yii\helpers\Url;
?>
<?= Tabs::widget([
    'id' => 'tab-comments-radio',
    'encodeLabels' => false,
    'items' => [
        [
            'label' => 'Комментарии <button class="btn btn-light btn-xs" id="btn-radio-comment-refresh" title="Обновить" alt="Обновить"><i class="fa fa-sync"></i></button>',
            'content' => '<div id="radio-comment-index" data-ajax-url="' . Url::to(['thirty/radio-comment-index', 'idRadio'=>$model->id]) . '"></div>',
            'linkOptions' => ['data-tab' => 'index'],
        ],
        [
            'label' => 'Добавить комментарий',
            'content' => '<div id="radio-comment-form-container" data-ajax-url="' . Url::to(['thirty/radio-comment-form', 'idRadio'=>$model->id]) . '"></div>',
            'linkOptions' => ['data-tab' => 'form'],
        ]
    ],
]) ?>
<?php $this->registerJS(<<<JS
    
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

    // привязка к кнопке обновить коментарии
    $('#btn-radio-comment-refresh').on('click', function() {
        runAjaxGetRequest($('#radio-comment-index'));
    });

    runAjaxGetRequest($('#radio-comment-index'));
    runAjaxGetRequest($('#radio-comment-form-container'));
    
JS
);
?>