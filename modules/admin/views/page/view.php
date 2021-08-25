<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\page\Page $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Страницы', 'url' => ['index', 'idTree' => $model->id_tree]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="page-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-group" style="margin-bottom: 10px;">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить страницу?',
                'method' => 'post',
            ],
        ]) ?>
    </div>

    <?= $this->render('/news/_view', [
        'model' => $model,
    ]); ?>

</div>
