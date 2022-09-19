<?php

/** @var yii\web\View $this */
/** @var app\models\department\Department $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

?>

<?php $form = ActiveForm::begin([ 
    'options' => [
        'class' => 'mv-form',
    ],
]); ?>

    <?= Html::errorSummary($model, ['class' => 'alert alert-danger']) ?>

    <?= $form->field($model, 'department_index')->textInput(['maxlength' => true])
        ->label($model->getAttributeLabel('department_index') . ' (например, 01)') ?>

    <?= $form->field($model, 'department_name')->textInput(['maxlength' => true]) ?>
    

    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary mt-3']) ?>

<?php ActiveForm::end(); ?>
