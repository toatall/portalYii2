<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RegEcr */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Анкетирование по ГР', 'url' => ['index', '']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="reg-ecr-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-group" style="margin-bottom: 20px;">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить запись?',
                'method' => 'post',
            ],
        ]) ?>
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
