<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Department\DepartmentCard */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="department-card-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_fio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_rank')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_position')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_telephone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_resp')->textarea(['rows' => 5]) ?>




    <div class="panel panel-default">
        <div class="panel-heading"><?= $model->getAttributeLabel('photoFile') ?></div>
        <div class="panel-body">
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
