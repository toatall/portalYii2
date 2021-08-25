<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Department\DepartmentCard $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="department-card-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_fio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_rank')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_position')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_telephone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_resp')->textarea(['rows' => 5]) ?>




    <div class="card">
        <div class="card-header"><?= $model->getAttributeLabel('photoFile') ?></div>
        <div class="card-body">
            <?php if (!$model->isNewRecord && $model->user_photo): ?>
            <?= $model->user_photo ?>
            <?= $form->field($model, 'deletePhotoFile')->checkbox() ?>
            <hr />
            <?php endif; ?>
            <?= $form->field($model, 'photoFile')->fileInput()->label('Загрузить') ?>
        </div>
    </div>

    <?= $form->field($model, 'user_level')->dropDownList([0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
