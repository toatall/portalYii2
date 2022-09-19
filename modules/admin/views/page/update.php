<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\page\Page $model */
/** @var app\models\Tree $modelTree */

$labelPages = 'Страницы';
if (!empty($modelTree)) {
    $labelPages .= ' раздела "' . $modelTree->name . '"';
}

$this->title = 'Изменение страницы: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => $labelPages, 'url' => ['index', 'idTree' => $model->id_tree]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="page-update">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('/news/_form', [
        'model' => $model,
        'urlBack' => ['index', 'idTree' => $model->id_tree],
    ]) ?>

</div>