<?php
/* @var $this \yii\web\View */
/* @var $model ThirtyRadio[] */

use app\models\thirty\ThirtyRadio;
use yii\helpers\Url;

$this->title = 'Радио 30FNS';
$this->params['breadcrumbs'][] = ['label'=>'30-летие налоговых органов', 'url'=>['/thirty/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="through-time">
    <h1 class="head"><?= $this->title ?></h1>

    <div class="row gallery">
        <?php foreach ($model as $item): ?>
            <div class="col-sm-3">
                <div class="panel panel-default">
                    <div class="panel-body text-center ">
                        <div class="radio-container">
                            <audio controls class="col-sm-12" style="display: none;">
                                <source src="<?= $item->getUrlFileName() ?>" />
                                <a href="<?= $item->getUrlFileName() ?>">Скачать</a>
                            </audio>
                            <button class="btn btn-success btn-play" data-url="<?= Url::to(['/thirty/radio-view', 'id' => $item->id]) ?>" data-id="<?= $item->id ?>">
                                <i class="fas fa-play"></i> Прослушать
                            </button>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-4">
                                <strong><?= $item->description ?></strong>
                            </div>
                            <div class="col-sm-8">
                                <div class="btn-group">
                                    <a href="<?= Url::to(['/thirty/radio-comment', 'id'=>$item->id]) ?>" class="mv-link btn btn-default" data-toggle="popover" data-content="Комментарии" data-placement="bottom">
                                        <i class="fas fa-comment text-warning"></i>
                                        <?= $item->count_comments ?>
                                    </a>
                                    <div class="btn btn-default" data-toggle="popover" data-content="Прослушано" data-placement="bottom">
                                        <i class="fas fa-play-circle text-success"></i>
                                        <text id="count_view_<?= $item->id ?>"><?= $item->count_view ?></text>
                                    </div>
                                    <a href="<?= Url::to(['/thirty/radio-like', 'id'=>$item->id]) ?>" class="btn-like btn btn-default" data-toggle="popover" data-content="Нравится" data-placement="bottom">
                                        <i class="fas fa-heart text-primary"></i> <?= $item->count_like ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php $this->registerJS(<<<JS
    
     $('.btn-play').on('click', function () {
        let radio = $(this).parent('div.radio-container').find('audio');
        radio.show();
        radio.get(0).play();
        $(this).hide();
        // send view
        let span_count = $('#count_view_' + $(this).attr('data-id'));
        $.get($(this).attr('data-url'))
            .done(function (data) {
                span_count.html(data);
            })
            .fail(function (jqXHR) {
                span_count.html('<i class="fas fa-times-circle text-danger" title="' + jqXHR.statusText + '"></i>');
            });
    });

    $('.btn-like').on('click', function () {
        let link = $(this);
        link.html('<i class="fas fa-spinner fa-spin"></i>');
        $.get($(this).attr('href'))
            .done(function (data) {
                link.html('<i class="fas fa-heart text-primary"></i> ' + data);
            })
            .fail(function (jqXHR) {
                link.html('<i class="fas fa-times-circle text-danger" title="' + jqXHR.statusText + '"></i>');
            });
        return false;
    });
    
JS
);
?>