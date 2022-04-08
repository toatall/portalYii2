<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Protocol $model */

$this->title = 'Изменение протокола: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Потоколы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="protocol-update">

    <h1 class="display-4 border-bottom"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
