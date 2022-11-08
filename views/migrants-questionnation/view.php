<?php

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\MigrantsQuestionnation $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Анкетирование мигрантов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="migrants-questionnation-view">

    <h1 class="display-5 border-bottom mv-hide"><?= Html::encode($this->title) ?></h1>

    <p class="mv-hide">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ul_name',
            'ul_inn',
            'ul_kpp',
            'date_send_notice:date',
            'region_migrate',
            'cause_migrate',
            'date_create:datetime',
            'date_update:datetime',
            'author',
        ],
    ]) ?>

</div>
