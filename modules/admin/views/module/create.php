<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Module $model */

$this->title = 'Создать модуль';
$this->params['breadcrumbs'][] = ['label' => 'Модули', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-create">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
