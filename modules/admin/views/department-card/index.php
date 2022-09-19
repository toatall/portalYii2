<?php

use yii\bootstrap5\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\department\Department $modelDepartment */

$this->title = 'Структура отдела "' . $modelDepartment->department_name . '"';
$this->params['breadcrumbs'][] = ['label' => 'Отделы', 'url' => ['/admin/department/index']];
$this->params['breadcrumbs'][] = ['label' => $modelDepartment->department_name, 'url' => ['/admin/department/view', 'id'=>$modelDepartment->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-card-index">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <p class="btn-group mt-2">
        <?= Html::a('Добавить сотрудника', ['create', 'idDepartment'=>$modelDepartment->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Назад', ['/admin/department/view', 'id'=>$modelDepartment->id], ['class' => 'btn btn-secondary']) ?>
    </p>


    <?= GridView::widget([
        'id' => 'grid-department-card-index',
        'pjax' => true,
        'responsive' => false,       
        'pager' => [
            'firstPageLabel' => 'Первая',
            'lastPageLabel' => 'Последняя',
        ],
        'dataProvider' => $dataProvider,
        'columns' => [
            // 'id',
            // 'id_department',
            [
                'format' => 'raw',
                'value' => function($model) {
                    /** @var app\models\department\DepartmentCard $model */
                    return Html::img($model->getUserPhotoFile(), ['style'=>'width:7rem;']);
                },
                // 'headerOptions' => ['class' => 'w-25 text-center'],
            ],
            'user_fio',
            'user_rank',
            'user_position',
            //'user_telephone',
            //'user_photo',
            //'user_level',
            //'sort_index',
            //'log_change',
            //'user_resp',
            //'date_create',
            //'date_edit',

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
