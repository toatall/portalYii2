<?php 
/** @var yii\web\View $this */
/** @var array $query */

$total = [
    'count_mark_five' => 0,
    'count_mark_four' => 0,
    'count_mark_three' => 0,
    'count_kpk' => 0,
];
?>
<table class="table table-bordered table-striped">
    <thead class="thead-light">
        <tr>
            <th rowspan="2" style="vertical-align: middle;">
                Территориальный налоговый орган Ханты-Мансийского автономного округа - Югры
            </th>
            <th colspan="5" style="text-align: center;">
                Результативность прохождения курсов повышения квалификации 
            </th>
        </tr>
        <tr>
            <th>Количество слушателей, сдавших итоговый тест на оценку "5"</th>
            <th>Количество слушателей, сдавших итоговый тест на оценку "4"</th>
            <th>Количество слушателей, сдавших итоговый тест на оценку "3"</th>
            <th>всего прошли <span data-toggle="tooltip" title="Курсы повышения квалификации" data-target="hover">КПК*</span>, чел</th>
            <th>Средняя арифметическая оценка за итоговый тест</th>
        </tr>        
    </thead>
    <tbody>
        <?php foreach($query as $item): 
            $total['count_mark_five'] += $item['count_mark_five'];
            $total['count_mark_four'] += $item['count_mark_four'];
            $total['count_mark_three'] += $item['count_mark_three'];
            $total['count_kpk'] += $item['count_kpk'];
        ?>
        <tr>
            <td><?= $item['name'] ?></td>
            <td>
                <code class="text-success fa-2x"><?= $item['count_mark_five'] ?></code>
            </td>
            <td>
                <code class="text-warning fa-2x"><?= $item['count_mark_four'] ?></code>
            </td>
            <td>
                <code class="text-danger fa-2x"><?= $item['count_mark_three'] ?></code>
            </td>
            <td>
                <code class="text-secondary fa-2x"><?= $item['count_kpk'] ?></code>
            </td>
            <td>
                <code class="text-secondary fa-2x"><?= Yii::$app->formatter->asDecimal($item['avg_mark'], 2) ?></code>
            </td>
        </tr>        
        <?php endforeach; ?>
        <tr>
            <th>Итого</th>
            <th>
                <code class="text-primary font-weight-bolder fa-2x"><?= $total['count_mark_five'] ?></code>
            </th>
            <th>
                <code class="text-primary font-weight-bolder fa-2x"><?= $total['count_mark_four'] ?></code>
            </th>
            <th>
                <code class="text-primary font-weight-bolder fa-2x"><?= $total['count_mark_three'] ?></code>
            </th>
            <th>
                <code class="text-primary font-weight-bolder fa-2x"><?= $total['count_kpk'] ?></code>
            </th>
            <th>&nbsp;</th>
        </tr>
    </tbody>
</table>
<?php $this->registerJs(<<<JS
    $('[data-toggle="tooltip"]').tooltip();
JS); ?>