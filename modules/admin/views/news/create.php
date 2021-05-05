<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\news\News */
/* @var $modelTree \app\models\Tree */

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
