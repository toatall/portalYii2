<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var $this yii\web\View */
/** @var $model app\models\Module */
/** @var $form yii\widgets\ActiveForm */
?>

<div class="module-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'only_one')->checkbox() ?>

    <?= $form->field($model, 'children_node')->checkbox() ?>

    <?= $form->field($model, 'dop_action')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dop_action_right_admin')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
