<?php

use app\modules\test\models\TestQuestion;
use kartik\range\RangeInput;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\test\models\TestQuestion $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="test-question-form">

    <?php $form = ActiveForm::begin([
        'options'=> ['enctype' => 'multipart/form-data', 'autocomplete'=>'off'],
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php if (!$model->test->show_right_answer): ?>
    <?= $form->field($model, 'type_question')->dropDownList([
        TestQuestion::TYPE_QUESTION_RADIO => 'выбор одного варианта',
        TestQuestion::TYPE_QUESTION_CHECK => 'выбор нескольких вариантов',
    ]) ?>
    <?php endif; ?>

    <?= $form->field($model, 'weight')->widget(RangeInput::class, [
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
