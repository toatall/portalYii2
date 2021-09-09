<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Group $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Группы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-group mb-2">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_organization',
            'name',
            'description',
            'sort',
            'date_create:datetime',
            'date_edit:datetime',
        ],
    ]) ?>

</div>
