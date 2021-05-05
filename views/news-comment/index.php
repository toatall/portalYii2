<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $query \app\models\news\NewsComment[] */
?>

<br /><br />
<ul class="media-list">            
    <?php foreach ($query as $model): ?>
    <li class="media">
        <div class="media-left">           
            <img src="/img/user-default.png" class="img-circle img-thumbnail" style="max-width: 80px;" />
        </div>
        <div class="media-body">        
            <div class="panel panel-default">
                <div class="panel-heading">
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
                        <?= ''//Html::a('<i class="fas fa-edit"></i>', ['/news-comment/update', 'id'=>$model->id], ['title' => 'Изменить', 'class' => 'close']) ?>
                    </div>
                    <h4><?= $model->modelUser->fio ?> (<?= $model->username ?>)</h4>
                    <?= \Yii::$app->formatter->asDateTime($model->date_create) ?>
                </div>
                <div class="panel-body">
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

