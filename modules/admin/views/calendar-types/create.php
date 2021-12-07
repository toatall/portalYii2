<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\CalendarTypes $model */

$this->title = 'Добавить запись';
$this->params['breadcrumbs'][] = ['label' => 'Типы мероприятий', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calendar-create">

    <p class="display-4 border-bottom mv-hide"><?= Html::encode($this->title) ?></h1>    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>