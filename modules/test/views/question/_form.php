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

    <?= $form->field($model, 'name')->textarea(['rows' => 4]) ?>

    <?php if (!$model->test->show_right_answer): ?>
    <?= $form->field($model, 'type_question')->dropDownList([
        TestQuestion::TYPE_QUESTION_RADIO => 'выбор одного варианта',
        TestQuestion::TYPE_QUESTION_CHECK => 'выбор нескольких вариантов',
        TestQuestion::TYPE_QUSTION_INPUT => 'ввод ответов пользователем',    
        TestQuestion::TYPE_QUESTION_STARS => 'оценка (выбор звезд)',
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

    <div class="card" id="div-input-answers">
        <div class="card-header">
            Пример
            <br /><code>
            {"answers":
                [ {"id": 1, "label" : "Текст перед полем ввода 1","right_value" : "правильный ответ"},
                    { "id": 2, "label" : "Текст перед полем ввода 2","right_value" : false}
                ]
            }           
            </code>
        </div>
        <div class="card-body">
            <?= $form->field($model, 'input_answers')->textarea([
                'rows' => 4,                 
                'style'=>'font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; color: #e83e8c;',
            ]) ?>
        </div>
    </div>    

    <hr />
    <div class="btn-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['/test/question/index', 'idTest' => $model->id_test], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$id_type_question = Html::getInputId($model, 'type_question');
$TYPE_QUSTION_INPUT = TestQuestion::TYPE_QUSTION_INPUT;
$this->registerJs(<<<JS
    function checkTypeQuestion() {
        $('#div-input-answers').toggle($('#$id_type_question').val() == $TYPE_QUSTION_INPUT);
    }
    $('#$id_type_question').on('change', function() {
        checkTypeQuestion();
    });
    checkTypeQuestion();
JS);
