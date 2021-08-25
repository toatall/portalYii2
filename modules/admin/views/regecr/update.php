<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\RegEcr $model */

$this->title = 'Изменение: #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Анкетирование по ГР', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#' . $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="reg-ecr-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
