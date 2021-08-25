<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\RegEcr $model */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Анкетирование по ГР', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reg-ecr-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
