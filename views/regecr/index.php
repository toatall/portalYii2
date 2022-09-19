<?php
/** @var yii\web\View $this */
/** @var array $resultQuery */
/** @var array $sum */
/** @var string $date1 */
/** @var string $date2 */

use yii\bootstrap5\Html;
use kartik\widgets\DatePicker;

$this->title = 'Анкетирование по ГР';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="regecr-index">
    
    <div class="row">
        <div class="col border-bottom mb-2">
            <p class="display-5">
            <?= $this->title ?>
            </p>    
        </div>    
    </div>
        
    <div class="btn-group">
        <?= Html::a('Детализация', ['detail'], ['class' => 'btn btn-secondary']) ?>
        <?= Html::a('В виде графика', ['chart'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <div class="row mt-2">
        <div class="col-6">
            <div class="card">
                <div class="card-header">Поиск</div>
                <div class="card-body">
                    <?= Html::beginForm(['index'], 'get') ?>
                    <div class="row">
                        <div class="col-5">
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
                        <div class="col-5">
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
                        <div class="col-2">
                            <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary col-12']) ?>
                        </div>
                    </div>
                    <?= Html::endForm() ?>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-bordered table-striped table-hover mt-2">
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