<?php

use yii\bootstrap4\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use app\models\department\Department;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

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
                    return Html::a('<i class="fas fa-users"></i> Структура', ['/admin/department-card/index', 'idDepartment'=>$model->id], ['class' => 'btn btn-secondary']);
                },
            ],
            'id',
            'id_tree',
            'id_organization',
            'department_index',
            'department_name',
            'date_create:datetime',

            ['class' => ActionColumn::class],
        ],
    ]); ?>


</div>
