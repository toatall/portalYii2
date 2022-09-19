<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Tree $model */

$this->title = 'Создание раздела';
$this->params['breadcrumbs'][] = ['label' => 'Структура', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tree-create">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
