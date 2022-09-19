<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\page\Page $model */
/** @var app\models\Tree $modelTree */

$labelPages = 'Страницы';
if (!empty($modelTree)) {
    $labelPages .= ' раздела "' . $modelTree->name . '"';
}

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => $labelPages, 'url' => ['index', 'idTree' => $model->id_tree]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-view">

    <h1 class="display-5 border-bottom"><?= Html::encode($this->title) ?></h1>

    <div class="btn-group" style="margin-bottom: 10px;">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить страницу?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Назад', ['index', 'idTree' => $model->id_tree], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?= $this->render('/news/_view', [
        'model' => $model,
    ]); ?>

</div>
