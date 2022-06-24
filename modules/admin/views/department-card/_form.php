<?php

use kartik\range\RangeInput;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Department\DepartmentCard $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="department-card-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'mv-form',
        ],
    ]); ?>

    <?= $form->field($model, 'user_fio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_rank')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_position')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_telephone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_resp')->textarea(['rows' => 5]) ?>

    <div class="card">
        <div class="card-header"><?= $model->getAttributeLabel('photoFile') ?></div>
        <div class="card-body">
            <?php if (!$model->isNewRecord && $model->user_photo): ?>
            <?= Html::img($model->user_photo, ['class'=>'img-thumbnail', 'style'=>'max-height: 10rem']) ?>
            <?= $form->field($model, 'deletePhotoFile')->checkbox() ?>
            <hr />
            <?php endif; ?>
            <?= $form->field($model, 'photoFile')->fileInput()->label('Загрузить') ?>
        </div>
    </div>
    
    <?= $form->field($model, 'user_level')->widget(RangeInput::class, [
        'options' => ['readonly' => true],
        'html5Options' => ['min' => 0, 'max' => 10],
        'html5Container' => ['style' => 'width: 30rem;'],
    ])->label($model->getAttributeLabel('user_level')) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
