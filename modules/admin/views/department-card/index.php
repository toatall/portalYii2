<?php

use yii\bootstrap4\Html;
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

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить сотрудника', ['create', 'idDepartment'=>$modelDepartment->id], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'id_department',
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

            ['class' => ActionColumn::class],
        ],
    ]); ?>


</div>
