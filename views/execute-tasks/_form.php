<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ExecuteTasks */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="execute-tasks-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'org_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_department')->textInput() ?>

    <?= $form->field($model, 'period')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'period_year')->textInput() ?>

    <?= $form->field($model, 'count_tasks')->textInput() ?>

    <?= $form->field($model, 'finish_tasks')->textInput() ?>

    <?= $form->field($model, 'date_create')->textInput() ?>

    <?= $form->field($model, 'date_update')->textInput() ?>

    <?= $form->field($model, 'log_change')->textInput() ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
