<?php

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\conference\VksFns $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'ВКС с ФНС', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vks-fns-view">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <div class="btn-group" style="margin-bottom: 20px;">
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
            'theme',
            'members_people',
            'date_start',
            'time_start_msk:boolean',
            'duration',
            'note',
            'date_create:datetime',
            'date_edit:datetime',
            // 'log_change',
        ],
    ]) ?>

</div>
