<?php

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\rating\RatingMain $model */
/** @var app\models\Tree $modelTree */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Виды рейтингов для раздела "' . $modelTree->name . '"', 'url' => ['index', 'idTree'=>$model->id_tree]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rating-main-view">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <div class="btn-group" style="margin-bottom: 10px;">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Назад', ['index', 'idTree' => $modelTree->id], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'tree.name',
            'name',
            'order_asc',
            'note',
            // 'log_change',
            'date_create:datetime',
            'author',
        ],
    ]) ?>

</div>
