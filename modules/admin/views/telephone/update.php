<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Telephone $model */

$this->title = 'Изменение справочника: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Телефонные справочники', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="telephone-update">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
