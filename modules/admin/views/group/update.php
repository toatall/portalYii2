<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\Group $model */

$this->title = 'Изменение группы: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Группы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="group-update">

    <h1 class="display-4 border-bottom"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
