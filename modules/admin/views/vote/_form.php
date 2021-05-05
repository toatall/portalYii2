<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\Organization;

/* @var $this yii\web\View */
/* @var $model app\models\vote\VoteMain */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vote-main-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_start')->widget(DatePicker::class, []) ?>

    <?= $form->field($model, 'date_end')->widget(DatePicker::class, []) ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <?= Html::activeLabel($model, 'organizations') ?>
        </div>
        <div class="panel-body">
            <?= $form->field($model, 'orgList')->checkboxList(ArrayHelper::map(Organization::find()->all(), 'code', 'name'))->label(false) ?>
        </div>
    </div>

    <?= $form->field($model, 'multi_answer')->checkbox() ?>

    <?= $form->field($model, 'count_answers')->textInput() ?>

    <?= $form->field($model, 'on_general_page')->checkbox() ?>

    <?= $form->field($model, 'description')->textarea(['rows'=>5]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
