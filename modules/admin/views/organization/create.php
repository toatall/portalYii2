<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\Organization $model */

$this->title = 'Создание организации';
$this->params['breadcrumbs'][] = ['label' => 'Организации', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organization-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
