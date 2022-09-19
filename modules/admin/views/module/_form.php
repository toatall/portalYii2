<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Module $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="module-form">

    <div class="card card-body">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'only_one')->checkbox() ?>

        <?= $form->field($model, 'children_node')->checkbox() ?>

        <?= $form->field($model, 'dop_action')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'dop_action_right_admin')->checkbox() ?>

        <hr />

        <div class="btn-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Назад', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
