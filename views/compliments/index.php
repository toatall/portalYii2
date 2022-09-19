<?php
/** @var yii\web\View $this */
/** @var array $files */

use yii\bootstrap5\Html;
use yii\helpers\Url;
use app\assets\fancybox\FancyboxAsset;
FancyboxAsset::register($this);

$this->title = 'Конкурс "Видеооткрытка"';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1 style="font-weight: bolder;"><?= $this->title ?></h1>
<hr />
<div class="panel panel-defaul">
    <div class="panel-body">
        <a href="/files_static/pozdravleniya/Приглашение.jpg" data-fancybox>
            <img src="/files_static/pozdravleniya/Приглашение.jpg" style="width:300px;" class="thumbnail" />
        </a>
    </div>
</div>
<div id="compliments-index">
    <table class="table table-bordered table-striped">
        <tr>
            <th>Наименование</th>
            <th style="width: 15vm">Голосов</th>
        </tr>
    <?php foreach ($files as $file): ?>
        <tr>
            <td>
                <div class="">
                    <h3><?= $file['title'] ?></h3>
                    <hr />
                    <div class="caption">
                        <?= Html::a(Html::tag('i', '', ['class' => 'fab fa-youtube text-danger']) . ' Просмотр', $file['file'], ['data-fancybox'=>'', 'class'=>'btn btn-default']) ?>
                        <?php if (date('Ymd') <= '20210112'): ?>
                            <?= Html::button(Html::tag('i', '', ['class' => 'far fa-thumbs-up'])
                                . ' Нравится',
                                ['class'=>'btn btn-' . ($file['is_liked'] ? 'primary' : 'default') . ' btn-like', 'data-href'=>Url::to(['like', 'filename'=>basename($file['file'])]), 'data-id-counter'=>md5($file['file'])]) ?>
                        <?php endif; ?>
                    </div>
                </div>

            </td>
            <td>
                <!--div class="tick" data-value="<?= $file['count_like']; ?>" id="<?= md5($file['file']) ?>">
                    <div data-layout="vertical">
                        <span data-view="flip"></span>
                    </div>
                </div-->
                <h1><?= $file['count_like']; ?></h1>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
</div>
<?php
$this->registerJs(<<<JS
    $('.btn-like').on('click', function() {
        var btn = $(this);
        btn.prop('disabled', true);
        $.get(btn.data('href'))
            .done(function(data) {
                $('#' + btn.data('id-counter')).attr('data-value', data);
            })
            .fail(function(err) {
                btn.html('<div class="alert-danger">' + err.responseText + '</div>');  
            })
            .always(function() {
                btn.prop('disabled', false);
            });         
        return false;
    });
JS
);
$this->registerCss(<<<CSS
    #compliments-index .tick-flip-panel {
        background-color: #333232;;
    }
CSS
);
?>
