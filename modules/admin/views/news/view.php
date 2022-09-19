<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\news\News $model */
/** @var app\models\Tree $modelTree */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости раздела "' . $modelTree->name . '"', 'url' => ['index', 'idTree' => $model->id_tree]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-view">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <div class="btn-group mb-2">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить новость?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Назад', ['index', 'idTree' => $model->id_tree], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?= $this->render('_view', [
        'model' => $model,
    ]) ?>

</div>
