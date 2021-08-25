<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\Menu\Menu $model */

$this->title = 'Добавление пукнта меню';
$this->params['breadcrumbs'][] = ['label' => 'Меню', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
