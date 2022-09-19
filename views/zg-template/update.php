<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\zg\ZgTemplate $model */

$this->title = 'Шаблоны ответов на однотипные обращения: #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны ответов на однотипные обращения', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#' . $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="zg-template-update">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
