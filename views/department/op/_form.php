<?php
/** @var yii\web\View $this */
/** @var app\models\OP $model */

use yii\bootstrap5\ActiveForm;
use kartik\file\FileInput;
use yii\bootstrap5\Html;

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

<div class="btn-group pt-2">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    <?= Html::a('Отмена', ['department/op'], ['class' => 'btn btn-secondary']) ?>
</div>

<?php ActiveForm::end(); ?>




