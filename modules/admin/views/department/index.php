<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\department\Department;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отделы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить отдел', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'format' => 'raw',
                'value' => function(Department $model) {
                    return Html::a('<i class="fas fa-users"></i> Структура', ['/admin/department-card/index', 'idDepartment'=>$model->id], ['class' => 'btn btn-default']);
                },
            ],
            'id',
            'id_tree',
            'id_organization',
            'department_index',
            'department_name',
            'date_create:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
