<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\WhatReading $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="what-reading-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'mv-form',
        ],
    ]); ?>

    <?= $form->errorSummary($model) ?>

    <div class="card mb-3">
        <div class="card-header">Фото</div>
        <div class="card-body">
            <?= $form->field($model, 'uploadImage')->fileInput()->label(false) ?>
            <?php if (!$model->isNewRecord && ($img = $model->getImage())): ?>
                <hr />
                <?= Html::img($img, ['style' => 'width: 10rem;', 'class' => 'img-thumbnail']) ?><br />
                <?= $form->field($model, 'deleteImage')->checkbox() ?>
            <?php endif; ?>
        </div>
    </div>

    <?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'writer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
