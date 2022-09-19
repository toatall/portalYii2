<?php

use yii\bootstrap5\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use kartik\grid\SerialColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Организации';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organization-index">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a('Создать организацию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'striped' => false,
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($model, $key, $index, $grid) {
            /** @var app\models\Organization $model */
            if ($model->date_end) {
                return ['class' => 'table-danger'];
            }
        },
        'columns' => [
            ['class' => SerialColumn::class],

            'code',
            'name',
            'sort',
            'date_create:datetime',
            'date_edit:datetime',

            [
                'class' => ActionColumn::class,
                'dropdown' => true,
            ],
        ],
        'toolbar' => [
            '{export}',
            '{toggleData}',
        ],
        'export' => [
            'showConfirmAlert' => false,
        ],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,       
        ],
    ]); ?>


</div>
