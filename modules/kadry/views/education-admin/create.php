<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\kadry\models\education\Education $model */

$this->title = 'Создание образовательной программы';
$this->params['breadcrumbs'][] = ['label' => 'Образовательные программы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="education-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
