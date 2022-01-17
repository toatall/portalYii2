<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\rookie\modules\fortboyard\models\FortBoyard */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Fort Boyards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="container bg-light mt-4 rounded p-4">
    <div class="fort-boyard-view">

        <h1 class="font-weight-bolder border-bottom"><?= Html::encode($this->title) ?></h1>

        <div class="btn-group mb-2">
            <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить?',
                    'method' => 'post',
                ],
            ]) ?>
            <?= Html::a('Отмена', ['/rookie/fortboyard/fortboyard'], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'id_team',
                'date_show:date',
                'title',
                'text',
                'date_create:datetime',
            ],
        ]) ?>

    </div>
</div>