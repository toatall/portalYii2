<?php

use yii\bootstrap\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\admin\models\Role $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="role-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-role">
        <?= $form->field($model, 'name')->textInput() ?>
        <?= $form->field($model, 'description')->textInput() ?>
        
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
