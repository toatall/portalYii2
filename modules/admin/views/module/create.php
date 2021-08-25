<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\Module $model */

$this->title = 'Создать модуль';
$this->params['breadcrumbs'][] = ['label' => 'Модули', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
