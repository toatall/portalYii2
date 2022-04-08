<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ChangeLegislationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="change-legislation-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'type_doc') ?>

    <?= $form->field($model, 'date_doc') ?>

    <?= $form->field($model, 'number_doc') ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'date_doc_1') ?>

    <?php // echo $form->field($model, 'date_doc_2') ?>

    <?php // echo $form->field($model, 'date_doc_3') ?>

    <?php // echo $form->field($model, 'status_doc') ?>

    <?php // echo $form->field($model, 'text') ?>

    <?php // echo $form->field($model, 'date_create') ?>

    <?php // echo $form->field($model, 'date_update') ?>

    <?php // echo $form->field($model, 'author') ?>

    <?php // echo $form->field($model, 'log_change') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
