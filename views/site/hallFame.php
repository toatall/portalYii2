<?php
/** @var yii\web\View $this */
/** @var app\models\HallFame $model */

use dosamigos\gallery\Carousel;

$this->title = 'Доска почета';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col border-bottom mb-2">
    <p class="display-4">
        <?= $this->title ?>
    </p>    
</div>

<?= Carousel::widget([
    'items' => $model->showPhoto(),
    'options' => [
        'data-interval' => intval($model->getInterval() * 1000),        
    ],
    'templateOptions' => [
        'class' => 'bg-light',
    ],
]) ?>