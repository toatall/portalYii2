<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\news\News $model */
/** @var app\models\Tree $modelTree */

$this->title = 'Добавление новости';
$this->params['breadcrumbs'][] = ['label' => 'Новости раздела "' . $modelTree->name . '"', 'url' => ['index', 'idTree'=>$modelTree->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-create">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
        'urlBack' => ['/admin/news/index', 'idTree' => $modelTree->id],
    ]) ?>

</div>
