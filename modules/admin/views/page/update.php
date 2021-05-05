<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\page\Page */

$this->title = 'Изменение страницы: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index', 'idTree' => $model->id_tree]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="page-update">

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