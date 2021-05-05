<?php

/* @var $this \yii\web\View */
/* @var $model \app\modules\events\models\ContestArts */
/* @var $data array */
use yii\helpers\Html;
use app\assets\fancybox\FancyboxAsset;

FancyboxAsset::register($this);

$statuses = [
    '1' => '<i class="text-success fas fa-check-circle"></i> Правильно',
    '0' => '<i class="text-danger fas fa-times-circle"></i> Не правильно',
    'null' => '<i class="text-warning fas fa-question-circle"></i> Требуется уточнение',
];
?>

<div class="row">
    <div class="col-sm-2">
        <?= Html::a(Html::img($model->image_original, ['class' => 'thumbnail', 'style' => 'width: 10em;']), $model->image_original, ['class' => 'fancybox']) ?>
    </div>
    <div class="col-sm-6">
        <table class="table table-bordered">
            <tr>
                <th>Автор</th>
                <td><?= $model->image_original_author ?></td>                
            </tr>
            <tr>
                <th>Название</th>
                <td><?= $model->image_original_title ?></td>
            </tr>
            <tr>
                <th>Отдел</th>
                <td><?= $model->department_name ?></td>
            </tr>
            <tr>
                <th>Количество ответов</th>
                <td><?= count($data) ?></td>
            </tr>
        </table>
    </div>
</div>

<?php $this->registerJs(<<<JS
    $('.fancybox').fancybox();   
JS
); ?>
<hr />
<div id="div-status-ajax"></div><br />

<div class="alert alert-light">
    <label style="cursor: pointer;">
        <?= Html::checkbox('check_show_all', false, ['id' => 'chk-show-all']) ?>
        Показать все ответы
    </label>
</div>

<table class="table table-bordered">
    <tr>
        <th>#</th>
        <th>Автор</th>
        <th>Название</th>
        <th>Кто ответил</th>       
        <th>Дата</th>
        <th>Статус</th>
        <th>Ответ правильный?</th>
    </tr>
    <?php foreach($data as $item): ?>
    <tr class="<?= ($item['is_right'] === '0' || $item['is_right'] === '1') ? 'is-set' : '' ?>">
        <td><?= $item['id'] ?></td>
        <td><?= $item['image_author'] ?></td>
        <td><?= $item['image_name'] ?></td>
        <td><?= $item['fio'] ?> (<?= $item['author'] ?>) - <?= $item['department'] ?></td>
        <td><?= Yii::$app->formatter->asDatetime($item['date_create']) ?></td>
        <td><?= $item['is_right'] === '1' ? $statuses['1'] : ($item['is_right'] === '0' ? $statuses['0'] : $statuses['null']) ?></td>
        <td>
            <div class="btn-group">
                <?= Html::a('Да', ['/events/contest-arts/set-answer-yes', 'idAnswer' => $item['id']], ['class' => 'btn btn-success answer']) ?>
                <?= Html::a('Нет', ['/events/contest-arts/set-answer-no', 'idAnswer' => $item['id']], ['class' => 'btn btn-danger answer']) ?>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?= $this->registerJs(<<<JS
    $('.answer').on('click', function() {
        let stAj = $('#div-status-ajax');
        let btnGroup = $(this).parent('div').parent('td').parent('tr').hide();
        stAj.html('<i class="fas fa-circle-notch fa-spin"></i> Выполняется сохранение...');
        $.get($(this).attr('href'))
        .done(function(res) {
            if (res == 'OK') {
                stAj.html('<div class="alert alert-success">OK</div>');
                btnGroup.hide();
            }
            else {
                stAj.html('<div class="alert alert-info">' + res + '</div>');
            }
        })
        .fail(function(res) {
            stAj.html('<div class="alert alert-danger">' + res.responseText + '</div>');
        });
        
        return false;
    });
        
    $('.is-set').hide();
    $('#chk-show-all').on('click', function() {
        $('.is-set').toggle($(this).is(':checked'));
    });
JS
); ?>