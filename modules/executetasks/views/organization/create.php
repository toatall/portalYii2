<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\modules\executetasks\models\ExecuteTasksDescriptionOrganization $model */

$this->title = 'Добавление';
$this->params['breadcrumbs'][] = ['label' => 'Исполнение задач', 'url' => ['/executetasks/default/index']];
$this->params['breadcrumbs'][] = ['label' => 'Настройка организаций', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="execute-tasks-description-organization-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
