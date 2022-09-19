<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Organization $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="organization-form">

    <div class="card card-body">
    
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'sort')->textInput() ?>
        
        <hr />

        <div class="btn-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Назад', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
