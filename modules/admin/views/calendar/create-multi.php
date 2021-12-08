<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\calendar\Calendar $model */
/** @var app\models\calendar\CalendarData $modelData */

$this->title = 'Добавить запись';
$this->params['breadcrumbs'][] = ['label' => 'Календарь', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calendar-create">

    <p class="display-4 border-bottom mv-hide"><?= Html::encode($this->title) ?></h1>    

    <?= $this->render('_form-multi', [
        'model' => $model,
        'modelData' => $modelData,
    ]) ?>

</div>