<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Department\Department $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Отделы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить отдел?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_tree',
            'id_organization',
            'department_index',
            'department_name',
            'use_card',
            'general_page_type',
            'general_page_id_tree',
            'author',
            'log_change',
            'date_create:datetime',
            'date_edit:datetime',
        ],
    ]) ?>

</div>
