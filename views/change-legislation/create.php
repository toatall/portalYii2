<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\ChangeLegislation $model */

$this->title = 'Создание';
$this->params['breadcrumbs'][] = ['label' => 'Изменение в законодательстве', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="change-legislation-create">

    <h1 class="display-5 border-bottom mb-4">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
