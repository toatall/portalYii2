<?php
/* @var $this yii\web\View */
/* @var $model app\modules\events\models\ContestArts */


/* @var $count_all int */
/* @var $count_right int */
extract($model->getStatistic());

?>

<div class="row">
    <div class="col-sm-4">
        <table class="table table-bordered">
            <tr>
                <th>Всего ответов</th>
                <td><?= $count_all ?></td>
            </tr>
            <tr>
                <th class="text-success">Правильных ответов</th>
                <td><?= $count_right ?></td>
            </tr>
            <tr>
                <th class="text-danger">Неверных ответов</th>
                <td><?= $count_all - $count_right ?></td>
            </tr>
        </table>
    </div>
</div>


