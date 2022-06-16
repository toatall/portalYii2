<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\executetasks\models\ExecuteTasksDescriptionOrganization */

$this->title = 'Update Execute Tasks Description Organization: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Execute Tasks Description Organizations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="execute-tasks-description-organization-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
