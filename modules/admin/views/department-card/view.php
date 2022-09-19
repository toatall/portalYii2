<?php

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\department\DepartmentCard $model */

$this->title = $model->user_fio;
$this->params['breadcrumbs'][] = ['label' => 'Отделы', 'url' => ['/admin/department/index']];
$this->params['breadcrumbs'][] = ['label' => $model->department->department_name, 
    'url' => ['/admin/department/view', 'id'=>$model->id_department]];
$this->params['breadcrumbs'][] = ['label' => 'Структура отдела "' . $model->department->department_name . '"', 
    'url' => ['index', 'idDepartment'=>$model->id_department]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-card-view">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <div class="btn-group" style="margin-bottom: 10px;">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Назад', ['index', 'idDepartment'=>$model->id_department], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'department.concatened',
            'user_fio',
            'user_rank',
            'user_position',
            'user_telephone',
            [
                'attribute' => 'user_photo',
                'format' => 'raw',
                'value' => function($model) {
                    /** @var app\models\department\DepartmentCard $model */
                    return Html::img($model->getUserPhotoFile(), ['style'=>'width:10rem;']);
                },
            ],
            'user_level',
            'sort_index',
            // 'log_change',
            'user_resp',
            'date_create:datetime',
            'date_edit:datetime',
        ],
    ]) ?>

</div>
