<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\rating\RatingData $model */
/** @var app\models\Tree $modelTree */

$this->title = 'Изменение рейтинга: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Виды рейтингов для раздела "' . $modelTree->name . '"', 
    'url' => ['/admin/rating/index', 'idTree'=>$modelTree->id]];
$this->params['breadcrumbs'][] = ['label' => $model->ratingMain->name, 
    'url' => ['/admin/rating/view', 'id'=>$model->id_rating_main]];
$this->params['breadcrumbs'][] = ['label' => 'Рейтинги', 'url' => ['index', 'idMain' => $model->id_rating_main]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>

<div class="rating-data-update">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
