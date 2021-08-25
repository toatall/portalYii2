<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\rating\RatingMain $model */
/** @var app\models\Tree $modelTree */

$this->title = 'Добавление нового вида рейтинга';
$this->params['breadcrumbs'][] = ['label' => 'Виды рейтингов', 'url' => ['index', 'idTree' => $modelTree->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rating-main-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
