<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\news\NewsComment[] $query */
?>

<ul class="media-list mt-4">            
    <?php foreach ($query as $model): ?>
    <li class="media mt-2">
        <img src="/img/user-default.png" class="img-thumbnail rounded-circle mr-2" style="max-width: 7em;" />
        <div class="media-body">        
            <div class="card">
                <div class="card-header">
                    <div class="text-center">
                        <?= Html::a('<i class="fas fa-trash"></i>', ['/news-comment/delete', 'id' => $model->id], [
                            'title' => 'Удалить',
                            'class' => 'close btn-comment-delete',
                            'style' => 'margin-left:7px;',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </div>
                    <h5><?= $model->modelUser->fio ?> (<?= $model->username ?>)</h5>
                    <small>
                        <?= $model->modelUser->organization_name ?>
                        <?php if ($model->modelUser->department): ?> (<?= $model->modelUser->department ?>) <br /><?php endif; ?>
                        <?= \Yii::$app->formatter->asDateTime($model->date_create) ?>
                    </small>
                </div>
                <div class="card-body border-top">
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
        $(this).html('<i class="fas fa-spinner fa-spin"></i>');
        
        let link = $(this).attr('href');
        $.ajax({
            url: link,
            type: 'POST',
            cache: false
        })
        .done(function() {
            runAjaxGetRequest($('#container-comment'));
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

