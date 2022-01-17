<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\rookie\modules\fortboyard\models\FortBoyard */

$this->title = 'Изменение: ' . $model->title;
?>
<div class="container bg-light mt-4 rounded p-4">
    <div class="fort-boyard-update">

        <h1 class="font-weight-bolder border-bottom"><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>
</div>