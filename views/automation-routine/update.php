<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AutomationRoutine $model */

$this->title = 'Изменить ПМ: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Автоматизация рутиных операций', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="automation-routine-update">

    <h1 class="display-5 border-bottom"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
