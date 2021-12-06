<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\Calendar $model */

$this->title = 'Изменение календаря: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Календарь', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="calendar-update">

    <p class="display-4 border-bottom mv-hide"><?= Html::encode($this->title) ?></h1>    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
