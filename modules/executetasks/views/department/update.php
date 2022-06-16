<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\executetasks\models\ExecuteTasksDepartment $model */

$this->title = 'Изменение отдела: ' . (isset($model->department->concatened) ? $model->department->concatened : null);
$this->params['breadcrumbs'][] = ['label' => 'Исполнение задач', 'url' => ['/executetasks/default/index']];
$this->params['breadcrumbs'][] = ['label' => 'Настройка отделов', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="execute-tasks-department-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
