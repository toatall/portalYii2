<?php

/** @var yii\web\View $this */
/** @var app\models\calendar\Calendar $modelCalendar */
/** @var app\models\calendar\CalendarData $model */

use yii\bootstrap4\Html;

$this->title = "Добавление события для даты '$modelCalendar->date'";
$this->params['breadcrumbs'][] = ['label' => 'Календарь', 'url' => ['/admin/calendar/index']];
$this->params['breadcrumbs'][] = ['label' => $modelCalendar->date, 'url' => ['/admin/calendar/view', 'id'=>$modelCalendar->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="calendar-data-create">

    <p class="display-4 border-bottom mv-hide"><?= Html::encode($this->title) ?></h1>    

    <?= $this->render('_form', [
        'model' => $model,
        'modelCalendar' => $modelCalendar,
    ]) ?>

</div>