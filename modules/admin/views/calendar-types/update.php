<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\calendar\CalendarTypes $model */

$this->title = 'Изменение типа мероприятия: ' . $model->type_text;
$this->params['breadcrumbs'][] = ['label' => 'Типы мероприятий', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->type_text, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="calendar-update">

    <p class="display-4 border-bottom mv-hide"><?= Html::encode($this->title) ?></h1>    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
