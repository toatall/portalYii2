<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\admin\models\Role $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="role-form">

    <div class="card card-body">
            
        <?php $form = ActiveForm::begin(); ?>

        <div class="form-group">
            <?= $form->field($model, 'name')->textInput() ?>
            <?= $form->field($model, 'description')->textInput() ?>
            <hr />
            
            <div class="btn-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Назад', ['index'], ['class' => 'btn btn-secondary']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
