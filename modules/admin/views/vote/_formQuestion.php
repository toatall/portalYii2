<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\Organization;

/* @var $this yii\web\View */
/* @var $model \app\models\vote\VoteQuestion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vote-main-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'text_question')->textarea(['rows' => 7]) ?>

    <?= $form->field($model, 'text_html')->widget(\mihaildev\ckeditor\CKEditor::class, []) ?>

    <hr />
    <div class="btn-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['index-question', 'idMain' => $model->id_main], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
