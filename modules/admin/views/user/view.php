<?php

use app\models\User;
use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <p class="btn-group">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'password',
            'username_windows',
            'fio',
            'default_organization',
            'current_organization',
            'blocked',           
            'telephone',
            'post',
            'rank',
            'about',
            'department',
            'hash',
            'organization_name',
            [
                'attribute' => 'roles',
                'value' => function(User $model) {
                    return implode('<br />', iterator_to_array($model->getRoles()));
                },
                'format' => 'raw',
            ],                        
            'date_create:datetime',
            'date_edit:datetime',
            'date_delete:datetime',
        ],
    ]) ?>

</div>