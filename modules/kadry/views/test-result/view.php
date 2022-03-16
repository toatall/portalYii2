<?php 
/** @var yii\web\View $this */
/** @var array $query */

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
            <th>всего прошли КПК*, чел</th>
            <th>Средняя арифметическая оценка за итоговый тест</th>
        </tr>        
    </thead>
    <tbody>
        <?php foreach($query as $item): ?>
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
    </tbody>
</table>