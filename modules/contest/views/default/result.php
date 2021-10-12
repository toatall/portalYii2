<?php

/** @var yii\web\View $this */

use yii\bootstrap4\Html;

/** @var app\modules\contest\models\HrResult $modelResult */
/** @var app\modules\contest\models\HrResultData[] $resultData */
/** @var array $resultNumbers */

$this->title = 'Результаты';

?>

<p class="display-1 mt-5 border-bottom text-center"><?= $this->title ?></p>

<div class="row justify-content-center">
    <div class="col-6 alert alert-info">
        <strong>Уважаемые коллеги!</strong>
        <br />Отдел кадров благодарит Вас за участие в игре и напоминает о необходимости называть свою фамилию и температуру дежурным сотрудникам каждое утро при входе!        
        <hr />
        Вы ответиили правильно на <?= $resultNumbers['right'] ?> из <?= $resultNumbers['right'] + $resultNumbers['wrong'] ?>
        <hr />
        <div class="btn-group">
            <?= Html::a('Играть еще раз', ['/contest/default/index'], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Вернуться на Портал', ['/site/index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>    
</div>

<div class="row justify-content-center">
    <table class="table table-bordered col-6">
        <thead>
            <tr>
                <th>ФИО</th>
                <th>Температура</th>
                <th>Указанная вами температура</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultData as $fio=>$item): ?>
            <tr class="<?= (floatval($item['temperature']) == floatval($item['temperature_user'])) ? 'table-success' : 'table-danger' ?>">
                <td><?= $fio ?></td>
                <td><?= $item['temperature'] ?>
                <td><?= $item['temperature_user'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>