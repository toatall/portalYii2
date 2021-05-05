<?php
/* @var $this \yii\web\View */
/* @var $model \app\models\HallFame */

use yii\bootstrap\Carousel;

$this->title = 'Доска почета';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= $this->title ?></h1>
<hr />

<?= Carousel::widget([
    'items' => $model->showPhoto(),
    'options' => [
        'data-interval' => intval($model->getInterval() * 1000),
    ],
]) ?>