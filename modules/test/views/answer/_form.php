<?php

use yii\bootstrap4\Html;
use yii\widgets\ActiveForm;
use kartik\range\RangeInput;

/* @var $this yii\web\View */
/* @var $model app\modules\test\models\TestAnswer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="test-answer-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off']]); ?>
   
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'attach_file')->fileInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'weight')->widget(RangeInput::class, [
        'html5Options' => [
            'min' => 0, 'max' => 5,
        ],
        'html5Container' => [
            'style' => 'width: 300px;',
        ],
    ]) ?>
   
   <hr />
    <div class="btn-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['/test/answer/index', 'idQuestion' => $model->id_test_question], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
