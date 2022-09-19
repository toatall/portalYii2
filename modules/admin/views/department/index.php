<?php

use yii\bootstrap5\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use app\models\department\Department;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Отделы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-index">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a('Добавить отдел', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'id' => 'grid-department-index',
        'pjax' => true,
        'responsive' => false,       
        'pager' => [
            'firstPageLabel' => 'Первая',
            'lastPageLabel' => 'Последняя',
        ],
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'format' => 'raw',
                'value' => function(Department $model) {
                    return Html::a('<i class="fas fa-users"></i> Структура', 
                        ['/admin/department-card/index', 'idDepartment'=>$model->id], 
                        ['class' => 'btn btn-secondary']);
                },
            ],
            // 'id',
            // 'id_tree',
            'id_organization',
            'department_index',
            'department_name',
            'date_create:datetime',

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
