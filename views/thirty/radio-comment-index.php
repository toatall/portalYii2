<?php
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var \app\models\thirty\ThirtyRadioComment[] $query */
?>

<ul class="media-list mt-4">            
    <?php foreach ($query as $model): ?>
    <li class="media mt-2">
        <img src="/img/user-default.png" class="img-thumbnail rounded-circle mr-2" style="max-width: 7em;" />
        <div class="media-body">        
            <div class="card">
                <div class="card-header">
                    <div class="text-center">
                        <?= Html::a('<i class="fas fa-trash"></i>', ['/thirty/radio-comment-delete', 'id' => $model->id], [
                            'title' => 'Удалить',
                            'class' => 'close btn-comment-delete',
                            'style' => 'margin-left:7px;',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить?',
                                'method' => 'post',
                            ],
                        ]) ?>
                        <?= ''//Html::a('<i class="fas fa-edit"></i>', ['/news-comment/update', 'id'=>$model->id], ['title' => 'Изменить', 'class' => 'close']) ?>
                    </div>
                    <h4><?= $model->modelUser->fio ?> (<?= $model->username ?>)</h4>
                    <?= \Yii::$app->formatter->asDateTime($model->date_create) ?>
                </div>
                <div class="card-body">
                    <div class="text-justiffy"><?= $model->comment ?></div>
                </div>
            </div>
        </div>
    </li>
    <?php endforeach; ?>
</ul>
<?php
$this->registerJs(<<<JS

    $('.btn-comment-delete').on('click', function() {
        if (!confirm('Вы уверены, что хотите удалить?')) {
            return false;
        }
        
        $(this).html('<i class="fas fa-spinner fa-spin"></i>');
        
        let link = $(this).attr('href');
        $.ajax({
            url: link,
            type: 'POST',
            cache: false
        })
        .done(function() {
            runAjaxGetRequest($('#radio-comment-index'));
        });
        
        return false;
    });
    
    
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

JS
);
?>

