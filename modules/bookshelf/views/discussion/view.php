<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\bootstrap4\Tabs;

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookShelfDiscussion $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Книжная полка', 'url' => ['/bookshelf/default/index']];
$this->params['breadcrumbs'][] = ['label' => 'Литературная дискуссия', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="book-shelf-discussion-view">

    <?= Html::a('<i class="fas fa-chevron-circle-left"></i> Назад', ['index'], ['class' => 'btn btn-secondary btn-sm']) ?>
    <p class="display-4 border-bottom">        
        <?= Html::encode($this->title) ?>
    </p>

    <div class="card">
        <div class="card-body">
            <?= $model->note ?>
        </div>
        <?php if ($model->isEditor()): ?>
        <div class="card-footer">
            <div class="btn-group mb-2">
                <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-outline-danger btn-sm',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить? Все комментарии также будут удалены!',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="mt-4">         
        <?= Tabs::widget([
            'id' => 'tab-comments',
            'encodeLabels' => false,
            'items' => [
                [
                    'label' => 'Комментарии <button class="btn btn-light btn-sm" style="line-height:0.7rem; font-size:0.7rem;" id="btn-comment-refresh" title="Обновить" alt="Обновить"><i class="fa fa-sync"></i></button>',                
                    'content' => '<div id="container-comment" data-ajax-url="' . Url::to(['/bookshelf/discussion-comment/index', 'idDiscussion'=>$model->id]) . '"></div>',
                    'linkOptions' => ['data-tab' => 'index'],
                ],
                [
                    'label' => 'Добавить комментарий',
                    'content' => '<div id="container-comment-form" data-ajax-url="' . Url::to(['/bookshelf/discussion-comment/create', 'idDiscussion'=>$model->id]) . '"></div>',
                    'linkOptions' => ['data-tab' => 'form'],
                ]
            ],
            'options' => [
                'class' => 'mt-2',
            ],
        ]) ?>
    </div>
    
</div>

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
    $('#btn-comment-refresh').on('click', function() {        
        runAjaxGetRequest($('#container-comment'));
    });
        
    runAjaxGetRequest($('#container-comment'));
    runAjaxGetRequest($('#container-comment-form'));
    
JS
);
?>