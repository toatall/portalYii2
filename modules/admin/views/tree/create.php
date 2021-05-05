<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tree */

$this->title = 'Создание раздела';
$this->params['breadcrumbs'][] = ['label' => 'Структура', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tree-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
