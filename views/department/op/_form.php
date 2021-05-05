<?php
/* @var $this \yii\web\View */
/* @var $model \app\models\OP */

use yii\bootstrap\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin([
    'id' => 'form-department',
    'options'=> ['enctype' => 'multipart/form-data', 'autocomplete'=>'off'],
]); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'file')->widget(FileInput::class, [
    'options' => [
        'accept' => 'files/*',
        'multiple' => false,
    ],
    'pluginOptions' => [
        'showUpload' => false,
        'showPreview' => false,
    ],
]) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>




