<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\page\PageSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\Tree $modelTree */

$this->title = 'Страницы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить страницу', ['create', 'idTree' => $modelTree->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $this->render('/news/_index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]);
    ?>


</div>
