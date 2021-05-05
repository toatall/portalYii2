<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $modelDepartment \app\models\department\Department */

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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
