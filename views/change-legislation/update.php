<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\ChangeLegislation $model */

$this->title = 'Изменение: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Изменение в законодательстве', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="change-legislation-update">

    <h1 class="display-4 border-bottom"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
