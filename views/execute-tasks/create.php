<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ExecuteTasks $model */

$this->title = 'Добавлене исполнения задач за период';
$this->params['breadcrumbs'][] = ['label' => 'Исполнение задач', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="execute-tasks-create">

    <div class="col border-bottom mb-2">
        <p class="display-4">
            <?= $this->title ?>
        </p>    
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
