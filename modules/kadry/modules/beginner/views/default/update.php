<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\modules\kadry\modules\beginner\models\Beginner $model */

$this->title = 'Изменить данные сотрудника: ' . $model->fio;
$this->params['breadcrumbs'][] = ['label' => 'Давайте знакомиться', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="beginner-update">

    <h1 class="display-5 border-bottom mb-4">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
