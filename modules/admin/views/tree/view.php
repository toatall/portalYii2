<?php

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Tree $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Структура', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="tree-view">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <p class="btn-group">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить раздел?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Назад', ['/admin/tree/index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_parent',
            'id_organization',
            'name',
            'module',
            'use_organization',
            'use_material',
            'use_tape',
            'sort',
            'author',
            // 'log_change',
            'param1',
            'disable_child',
            'alias',
            'date_create',
            'date_edit',
            'date_delete',
        ],
    ]) ?>

</div>
