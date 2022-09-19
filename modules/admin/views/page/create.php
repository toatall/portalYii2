<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\page\Page $model */
/** @var app\models\Tree $modelTree */

$labelPages = 'Страницы';
if (!empty($modelTree)) {
    $labelPages .= ' раздела "' . $modelTree->name . '"';
}
$this->title = 'Добавление страницы';
$this->params['breadcrumbs'][] = ['label' => $labelPages, 'url' => ['index', 'idTree' => $modelTree->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-create">

    <h1 class="display-5 border-bottom"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('/news/_form', [
        'model' => $model,
        'urlBack' => ['index', 'idTree' => $modelTree->id]
    ]) ?>

</div>
<?php
// убрать поле on_general_page
$this->registerJs("$('#" . Html::getInputId($model, 'on_general_page') . "').parents('div.form-group').remove();");
// убрать поле tags
//$this->registerJs("$('#" . Html::getInputId($model, 'tags') . "').parents('div.form-group').remove();");
?>
