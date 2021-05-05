<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\page\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $modelTree \app\models\Tree */

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
