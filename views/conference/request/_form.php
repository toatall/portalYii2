<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use kartik\widgets\TimePicker;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;
use app\models\conference\AbstractConference;

/** @var yii\web\View $this */
/** @var app\models\conference\Conference $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="conference-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off']]); ?>

    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'type_conference')->widget(Select2::class, [
        'data' => AbstractConference::getTypes(),
    ]) ?>
    
    <?= $form->field($model, 'theme')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'members_people')->textarea(['rows' => 5]) ?>

    <?= $form->field($model, 'date_start')->widget(DateTimePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'todayBtn' => true,
            'autoclose' => true,
            'format' => 'dd.mm.yyyy hh:ii'
        ],
    ]) ?>

    <?= $form->field($model, 'duration')->widget(TimePicker::class, [
        'pluginOptions' => [
            'showMeridian' => false,
            'defaultTime' => '01:00',
        ],
    ])->label($model->getAttributeLabel('duration') . ' (ЧЧ:ММ)') ?>

    <?= $form->field($model, 'arrPlace')->widget(Select2::class, [
        'data' => $model->dropDownListLocation(),
        'options' => [
            'multiple' => true,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'is_confidential')->checkbox() ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 10]) ?>

    <div class="btn-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['request'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
