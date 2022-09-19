<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\zg\ZgTemplate $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="zg-template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kind')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput() ?>
    
    <div class="btn-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['zg-template/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
