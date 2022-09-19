<?php
/** @var yii\web\View $this */
/** @var array $data */
/** @var array $wrong */

use yii\bootstrap5\Html;

if ($data['count_all'] == 0) {
    $persent = 0;
}
else {
    $persent = round(floatval($data['count_right'] / $data['count_all']) * 100);
}
?>
<div class="christmas-calendar-guess row">
    <div class="col-sm-3">
        <div class="thumbnail">
            <?= Html::img($data['photo'], ['style'=>'max-width: 300px;']) ?>
            <hr />
            <div class="caption">
                <?= $data['description'] ?>
            </div>
        </div>
    </div>
    <div class="col-sm-9">
        <div class="panel panel-default">
            <div class="panel-heading">Статистика</div>
            <div class="panel-body">
                Ответов: <?= $data['count_all'] ?><br />
                Правильных ответов: <?= $data['count_right'] ?><br /><br />
                <div class="progress">
                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?= $persent ?>" aria-valuemax="100" aria-valuemin="0" style="width: <?= $persent ?>%;">
                        <?= $persent ?>%
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">А еще думали что это:</div>
            <div class="panel-body">
                <?php if (count($wrong) > 0): ?>
                <table class="table table-bordered">
                    <tr>
                        <th>ФИО</th>
                        <th>Количество голосов</th>
                    </tr>
                    <?php foreach ($wrong as $item): ?>
                    <tr>
                        <td><?= $item['fio'] ?></td>
                        <td><?= $item['answers'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <?php else: ?>
                Все ответили правильно!
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

