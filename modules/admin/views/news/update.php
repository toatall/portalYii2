<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\news\News $model */
/** @var app\models\Tree $modelTree */

$this->title = 'Изменение новости: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости раздела "' . $modelTree->name . '"', 'url' => ['index', 'idTree' => $model->id_tree]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="news-update">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
        'urlBack' => ['/admin/news/index', 'idTree'=>$model->id_tree],
    ]) ?>

</div>
