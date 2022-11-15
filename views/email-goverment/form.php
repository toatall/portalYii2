<?php

/** @var yii\web\View $this */
/** @var app\models\zg\EmailGoverment $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

?>

<?php $form = ActiveForm::begin([ 
    'options' => [
        'class' => 'mv-form',
    ],
]); ?>

    <div class="row">
        <div class="col-12">
            <?= Html::errorSummary($model, ['class' => 'alert alert-danger']) ?>
        </div>
        <div class="col-12">
            <?= $form->field($model, 'org_name')->textInput(['maxlength' => true]) ?>
        </div>
        
        <div class="col-12">
            <?= $form->field($model, 'ruk_name')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-6">
            <?= $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-12">
            <?= $form->field($model, 'post_address')->textarea(['rows' => 3]) ?>
        </div>

        <div class="col-12 border-top">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary mt-3']) ?>
        </div>
    
    </div>    

<?php ActiveForm::end(); ?>