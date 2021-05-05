<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отделы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="list-group">
    <?php foreach ($dataProvider->getModels() as $item): ?>
        <?= Html::a($item['department_index'] . '. ' . $item['department_name'], ['/department/view', 'id'=>$item['id']], ['class'=>'list-group-item']) ?>
    <?php endforeach; ?>
    </div>

</div>
