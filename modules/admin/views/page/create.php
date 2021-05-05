<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\page\Page */
/* @var $modelTree \app\models\Tree */

$this->title = 'Добавление страницы';
$this->params['breadcrumbs'][] = ['label' => 'Страницы', 'url' => ['index', 'idTree' => $modelTree->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('/news/_form', [
        'model' => $model,
    ]) ?>

</div>
<?php
// убрать поле on_general_page
$this->registerJs("$('#" . Html::getInputId($model, 'on_general_page') . "').parents('div.form-group').remove();");
// убрать поле tags
$this->registerJs("$('#" . Html::getInputId($model, 'tags') . "').parents('div.form-group').remove();");
?>
