<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Мероприятия';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="events-default-index">
    <h1><?= $this->title ?></h1>
    <hr />
    
    <div class="list-group">
        <?= Html::a('Конкурс "Навстречу искусству"', ['/events/contest-arts'], ['class' => 'list-group-item']) ?>
    </div>
    
</div>
