<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\rating\RatingData $model */
/** @var app\models\rating\RatingMain $modelRatingMain */
/** @var app\models\Tree $modelTree */

$this->title = 'Добавление рейтинга';
$this->params['breadcrumbs'][] = ['label' => 'Виды рейтингов для раздела "' . $modelTree->name . '"', 
    'url' => ['/admin/rating/index', 'idTree'=>$modelTree->id]];
$this->params['breadcrumbs'][] = ['label' => $modelRatingMain->name, 
    'url' => ['/admin/rating/view', 'id'=>$modelRatingMain->id]];
$this->params['breadcrumbs'][] = ['label' => 'Рейтинги', 'url' => ['index', 'idMain' => $modelRatingMain->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rating-data-create">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,       
    ]) ?>

</div>
