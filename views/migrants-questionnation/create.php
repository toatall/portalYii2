<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\MigrantsQuestionnation $model */

$this->title = 'Добавление';
$this->params['breadcrumbs'][] = ['label' => 'Анкетирование мигрантов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="migrants-questionnation-create">

    <h1 class="display-5 border-bottom mv-hide"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
