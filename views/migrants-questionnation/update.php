<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\MigrantsQuestionnation $model */

$this->title = 'Изменение записи: ' . $model->ul_name;
$this->params['breadcrumbs'][] = ['label' => 'Анкетирование мигрантов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="migrants-questionnation-update">

    <h1 class="display-5 border-bottom mv-hide"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
