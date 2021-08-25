<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\news\News $model */
/** @var app\models\Tree $modelTree */

$this->title = 'Добавление новости';
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index', 'idTree'=>$modelTree->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
