<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\CalendarData $model */
/** @var app\models\Calendar $modelCalendar */

$this->title = 'Изменить событие: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Календрь', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="calendar-update">

    <p class="display-4 border-bottom mv-hide"><?= Html::encode($this->title) ?></h1>    

    <?= $this->render('_form', [
        'model' => $model,
        'modelCalendar' => $modelCalendar,
    ]) ?>

</div>
