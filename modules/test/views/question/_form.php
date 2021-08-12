<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\test\models\TestQuestion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="test-question-form">

    <?php $form = ActiveForm::begin([
        'options'=> ['enctype' => 'multipart/form-data', 'autocomplete'=>'off'],
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type_question')->dropDownList([
        \app\modules\test\models\TestQuestion::TYPE_QUESTION_RADIO => 'выбор одного варианта',
        \app\modules\test\models\TestQuestion::TYPE_QUESTION_CHECK => 'выбор нескольких вариантов',
    ]) ?>

    <?= $form->field($model, 'weight')->widget(\kartik\range\RangeInput::class, [
        'html5Container' => [
            'style' => 'width:350px;',
        ],
        'html5Options' => [
            'min' => 1,
            'max' => 10,
        ],
    ]) ?>

    <?= $form->field($model, 'attach_file')->fileInput(['maxlength' => true]) ?>
    <hr />
    <div class="btn-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['/test/question/index', 'idTest' => $model->id_test], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
