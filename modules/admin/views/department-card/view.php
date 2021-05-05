<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Department\DepartmentCard */

$this->title = $model->user_fio;
$this->params['breadcrumbs'][] = ['label' => 'Отделы', 'url' => ['/admin/department/index']];
$this->params['breadcrumbs'][] = ['label' => $model->department->department_name, 'url' => ['/admin/department/view', 'id'=>$model->id_department]];
$this->params['breadcrumbs'][] = ['label' => 'Структура отдела "' . $model->department->department_name . '"', 'url' => ['index', 'idDepartment'=>$model->id_department]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="department-card-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-group" style="margin-bottom: 10px;">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить?',
                'method' => 'post',
            ],
        ]) ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_department',
            'user_fio',
            'user_rank',
            'user_position',
            'user_telephone',
            'user_photo',
            'user_level',
            'sort_index',
            'log_change',
            'user_resp',
            'date_create:datetime',
            'date_edit:datetime',
        ],
    ]) ?>

</div>
