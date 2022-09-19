<?php
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\news\NewsComment[] $query */
?>

<div class="row mt-4">            
    <?php foreach ($query as $model): ?>
    <div class="col-2 mt-2" style="max-width: 10em;">
        <a href="/@<?= $model->username ?>" target="_blank">
            <img src="<?= $model->modelUser->getPhotoProfile() ?>" class="img-thumbnail rounded mr-2" />
        </a>
    </div>
    <div class="col">        
        <div class="card">
            <div class="card-header">
                <div class="text-center">
                    <?= Html::a('<i class="fas fa-trash"></i>', ['/news-comment/delete', 'id' => $model->id], [
                        'title' => 'Удалить',
                        'class' => 'float-end btn-comment-delete',
                        'style' => 'margin-left:7px;',
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите удалить?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
                <h5>
                    <?= Html::a($model->modelUser->fio . ' (@' . $model->username . ')', '/@' . $model->username, 
                        ['class' => 'author', 'target' => '_blank']) ?>
                </h5>
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
    <?php endforeach; ?>
</div>
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

