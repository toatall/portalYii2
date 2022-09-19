<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\modules\kadry\models\education\Education $model */

$this->title = 'Создание образовательной программы';
$this->params['breadcrumbs'][] = ['label' => 'Образовательные программы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="education-create">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
