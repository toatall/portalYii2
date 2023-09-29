<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\modules\contest\modules\pets\models\Pets $model */

$this->title = 'Изменение животного: ' . $model->pet_name;
$this->params['breadcrumbs'][] = ['label' => $model->pet_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="pets-update">

    <h1 class="display-5 border-bottom"><?= Html::encode($this->title) ?></h1>

    <div class="card card-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>
