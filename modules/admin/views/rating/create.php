<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\rating\RatingMain */
/* @var $modelTree \app\models\Tree */

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
