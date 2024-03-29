<?php

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Group $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Группы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-view">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

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

    <?php if ($model->groupUsers): ?>
    <div class="card">
        <div class="card-header">Подключенные пользователи</div>
        <div class="card-body">
            <ul class="list-unstyled">
            <?php foreach ($model->groupUsers as $user): ?>
                <li><i class="fas fa-user"></i> <?= $user->fio ?> (<?= $user->username ?>)</li>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>

</div>
