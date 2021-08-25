<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\conference\VksUfns $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'ВКС с УФНС', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="vks-ufns-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-group" style="margin-bottom: 20px;">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить?',
                'method' => 'post',
            ],
        ]) ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'theme',
            'responsible',
            'members_people',
            'members_organization',
            'date_start',
            'duration',
            'note',
            'date_create:datetime',
            'date_edit:datetime',
            'log_change',
        ],
    ]) ?>

</div>
