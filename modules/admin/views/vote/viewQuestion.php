<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \app\models\vote\VoteQuestion */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Голосование', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->main->name, 'url' => ['index-question', 'idMain'=>$model->id_main]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="vote-question-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-group">
        <?= Html::a('Изменить', ['update-question', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete-question', 'id' => $model->id], [
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
            'text_question',
            'date_create',
            'date_edit',
            [
                'attribute' => 'log_change',
                'value' => function(\app\models\vote\VoteQuestion $model) {
                    return \app\helpers\Log\LogHelper::getLog($model->log_change);
                },
                'format' => 'raw',
            ],
        ],
    ]) ?>

</div>
