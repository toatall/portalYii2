<?php

use app\helpers\Log\LogHelper;
use app\models\Module;
use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Module $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Модули', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-view">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <p class="btn-group">
        <?= Html::a('Изменить', ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->name], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить модуль?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description',
            'only_one',
            [
                'attribute' => 'log_change',
                'value' => function(Module $model) {
                    return LogHelper::getLog($model->log_change);
                },
                'format' => 'raw',
            ],
            'date_create:datetime',
            'author',
        ],
    ]) ?>

</div>
