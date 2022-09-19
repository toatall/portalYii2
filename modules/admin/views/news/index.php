<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\news\NewsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\Tree $modelTree */

$this->title = 'Новости раздела "' . $modelTree->name . '"';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a('Добавить новость', ['create', 'idTree' => $modelTree->id], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= $this->render('_index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]);
    ?>


</div>