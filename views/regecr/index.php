<?php
/* @var $this \yii\web\View */
/* @var $resultQuery array */
/* @var $sum array */
/* @var $date1 string */
/* @var $date2 string */

use yii\helpers\Html;
use kartik\widgets\DatePicker;

$this->title = 'Анкетирование по ГР';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="regecr-index row">
    <h1><?= $this->title ?></h1>

    <hr />
    <div class="btn-group">
        <?= Html::a('Детализация', ['detail'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('В виде графика', ['chart'], ['class' => 'btn btn-default']) ?>
    </div>
    <hr />

    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">Поиск</div>
            <div class="panel-body">
                <?= Html::beginForm(['index'], 'get') ?>
                <div class="row">
                    <div class="col-sm-5">
                        <?= DatePicker::widget([
                            'name' => 'date1',
                            'value' => $date1,
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'todayBtn' => true,
                                'autoclose' => true,
                            ],
                        ]) ?>
                    </div>
                    <div class="col-sm-5">
                        <?= DatePicker::widget([
                            'name' => 'date2',
                            'value' => $date2,
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'todayBtn' => true,
                                'autoclose' => true,

                            ],
                        ]) ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary col-sm-12']) ?>
                    </div>
                </div>
                <?= Html::endForm() ?>
            </div>
        </div>
    </div>

    <table class="table table-bordered table-striped table-hover">
        <tr>
            <th>Наименование НО</th>
            <th>Кол-во вновь созданных ООО</th>
            <th>Кол-во опрошенных</th>
            <th>Средняя оценка А 1.1 (средннее значение) <span class="badge badge-info" data-toggle="popover" data-placement="left" data-content='Средняя оценка респондентами по показателю А 1.1 "Среднее время регистрации, юридических лиц", дней (среднее арифметическое от общего количества опрошенных респондентов)'><i class="fa fa-info"></i></span></th>
            <th>Средняя оценка А 1.2 (средннее значение) <span class="badge badge-info" data-toggle="popover" data-placement="left" data-content='Средняя оценка респондентами по показателю А 1.2 "Среднее количество процедур, необходимых для регистрации юридических лиц", штук (среднее арифметическое от общего количества опрошенных респондентов)'><i class="fa fa-info"></i></span></th>
            <th>Средняя оценка А 1.3 (средннее значение) <span class="badge badge-info" data-toggle="popover" data-placement="left" data-content='Средняя оценка респондентами по показателю А 1.3 "Оценка деятельности органов власти по регистрации, юридических лиц", баллов (среднее арифметическое от общего количества опрошенных респондентов)'><i class="fa fa-info"></i></span></th>
        </tr>
        <tr>
            <th>8600</th>
            <th><?= $sum['count_create'] ?></th>
            <th><?= $sum['count_vote'] ?></th>
            <th><?= round($sum['avg_eval_a_1_1'], 2) ?></th>
            <th><?= round($sum['avg_eval_a_1_2'], 2) ?></th>
            <th><?= round($sum['avg_eval_a_1_3'], 2) ?></th>
        </tr>
        <?php foreach ($resultQuery as $result): ?>
            <tr>
                <td><?= $result['code_org'] ?></td>
                <td><?= $result['count_create'] ?></td>
                <td><?= $result['count_vote'] ?></td>
                <td><?= $result['avg_eval_a_1_1'] ?></td>
                <td><?= $result['avg_eval_a_1_2'] ?></td>
                <td><?= $result['avg_eval_a_1_3'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php
$this->registerJs(<<<JS
    $('[data-toggle="popover"]').popover({trigger: 'hover'});
JS
);
?>