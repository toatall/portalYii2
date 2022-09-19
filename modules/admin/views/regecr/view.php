<?php

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\regecr\RegEcr $model */

$this->title = 'Запись ИФНС ' . $model->code_org .' от ' . $model->date_reg;
$this->params['breadcrumbs'][] = ['label' => 'Анкетирование по ГР', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reg-ecr-view">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <div class="btn-group mb-3">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить запись?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'code_org',
            'date_reg:date',
            'count_create',
            'count_vote',
            'avg_eval_a_1_1',
            'avg_eval_a_1_2',
            'avg_eval_a_1_3',
            'author',
            'date_create:datetime',
            'date_update:datetime',
        ],
    ]) ?>

</div>
