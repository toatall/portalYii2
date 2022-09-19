<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\rating\RatingMain $model */
/** @var app\models\Tree $modelTree */

$this->title = 'Добавление нового вида рейтинга';
$this->params['breadcrumbs'][] = ['label' => 'Виды рейтингов для раздела "' . $modelTree->name . '"', 'url' => ['index', 'idTree' => $modelTree->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rating-main-create">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelTree' => $modelTree,
    ]) ?>

</div>
