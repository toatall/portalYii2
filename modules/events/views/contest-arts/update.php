<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\events\models\ContestArts */

$this->title = 'Изменение картины: ' . $model->image_original_title;
$this->params['breadcrumbs'][] = ['label' => 'Конкурс "Навстречу искусству"', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['admin']];
$this->params['breadcrumbs'][] = ['label' => $model->image_original_title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="contest-arts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
