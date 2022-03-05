    <?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvide $dataProvider */

?>

<?php if (($items = $dataProvider->getModels()) == null): ?>
    <div class="alert mt-2 text-muted">
        Нет комментариев
    </div>
<?php else: ?>
    <ul class="media-list mt-4"> 
        <?php foreach ($dataProvider->getModels() as $model): ?>
        <li class="media mt-2">
            <a href="/@<?= $model->username ?>" target="_blank">
                <img src="<?= $model->userModel->getPhotoProfile() ?>" class="img-thumbnail rounded mr-2" style="max-width: 10em;" />
            </a>
            <div class="media-body">        
                <div class="card">
                    <div class="card-header">
                        <div class="text-center">
                            <?php if ($model->isEditor()): ?>
                            <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model->id], [
                                'title' => 'Удалить',
                                'class' => 'close btn-comment-delete',
                                'style' => 'margin-left:7px;',
                                'data' => [
                                    'confirm' => 'Вы уверены, что хотите удалить?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                            <?php endif; ?>
                        </div>
                        <h5>
                            <?= Html::a($model->userModel->fio . ' (@' . $model->username . ')', '/@' . $model->username, ['class' => 'author', 'target' => '_blank']) ?>
                        </h5>
                        <small>
                            <?= $model->userModel->organization_name ?>
                            <?php if ($model->userModel->department): ?> (<?= $model->userModel->department ?>) <br /><?php endif; ?>
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
<?php endif; ?>
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
