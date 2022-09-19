<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\Organization $modelOrganization */

$this->title = 'Отделы (' . ($modelOrganization->name ?? null) . ')';
$this->params['breadcrumbs'][] = ['label' => 'Отделы', 'url' => ['/department/index', 'org'=>$modelOrganization->code]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-index">

    <div class="col border-bottom mb-2">
        <p class="display-5">
            <?= $this->title ?>
        </p>    
    </div>

    <div class="list-group">
    <?php foreach ($dataProvider->getModels() as $item): ?>
        <?= Html::a($item['department_index'] . '. ' . $item['department_name'], ['/department/view', 'id'=>$item['id']], ['class'=>'list-group-item list-group-item-action']) ?>
    <?php endforeach; ?>
    </div>

</div>
