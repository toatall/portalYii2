<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\vote\VoteMain */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Голосование', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="vote-main-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-group">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Управление вопросами', ['index-question', 'idMain' => $model->id], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <br /><br />

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'date_start',
            'date_end',
            'organizations',
            'multi_answer',
            'on_general_page',
            'description',
            'date_create',
            'date_edit',
            'log_change',
        ],
    ]) ?>

</div>
