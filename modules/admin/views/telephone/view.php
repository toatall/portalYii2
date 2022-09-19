<?php

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Telephone $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Телефонные справочники', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="telephone-view">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <p class="btn-group">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_organization',
            [
                'attribute' => 'telephone_file',
                'value' => function($model) {
                    return Html::a(basename($model->telephone_file), $model->telephone_file, ['target'=>'_blank']);
                },
                'format' => 'raw',
            ],
            'dop_text',
            'sort',
            'date_create:datetime',
            'date_edit:datetime',
            'author',
            // 'log_change',
            // 'count_download',
        ],
    ]) ?>

</div>
