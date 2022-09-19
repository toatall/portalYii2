<?php
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\calendar\CalendarTypes $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="calendar-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'data-pjax' => true,
        ],
    ]); ?>

    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'type_text') ?>

    <div class="border-top">
        <div class="btn-group  pt-1">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Отмена', ['/calendar/calendar-types/index'], ['class' => 'btn btn-secondary', 'pjax' => 1]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
