<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\page\PageSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\Tree $modelTree */

$this->title = 'Страницы';
if (!empty($modelTree)) {
    $this->title .= ' раздела "' . $modelTree->name . '"';
}
$this->params['breadcrumbs'][] = $this->title;
$isAjax = Yii::$app->request->isAjax;
?>
<div class="page-index">
    
    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>
    
    <p class="mt-3 btn-group">
        <?= Html::a('Добавить страницу', ['create', 'idTree' => $modelTree->id], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= $this->render('/news/_index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ])
    ?>


</div>
