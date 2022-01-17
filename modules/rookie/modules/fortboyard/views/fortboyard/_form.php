<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\rookie\modules\fortboyard\models\FortBoyard $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="fort-boyard-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_team')->widget(Select2::class, [
        'data' => $model->dropDownTeams(),
    ]) ?>

    <?= $form->field($model, 'date_show')->widget(DatePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'todayBtn' => true,
            'autoclose' => true,
            'startDate' => date('d.m.Y'),
        ],
    ]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <div class="btn-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['/rookie/fortboyard/fortboyard'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
