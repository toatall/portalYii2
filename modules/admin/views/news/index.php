<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\news\NewsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var \app\models\Tree $modelTree */

$this->title = 'Новости';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить новость', ['create', 'idTree' => $modelTree->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= $this->render('_index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]);
    ?>


</div>