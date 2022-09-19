<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\rating\RatingMain $model */
/** @var app\model\Tree $modelTree */

$this->title = 'Изменение вида рейтинга: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Виды рейтингов для раздела "' . $modelTree->name . '"', 'url' => ['index', 'idTree' => $model->id_tree]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="rating-main-update">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelTree' => $modelTree,
    ]) ?>

</div>
