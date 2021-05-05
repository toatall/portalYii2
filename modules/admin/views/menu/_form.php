<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Menu\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <?= Html::activeLabel($model,'type_menu'); ?>:
            <?php
            switch ($model->type_menu)
            {
                case 1: echo 'Верхнее меню'; break;
                case 2: echo 'Левое меню'; break;
            }
            ?>
        </div>
    </div>

    <?= $form->field($model, 'id_parent')->dropDownList(['0' => 'Родитель']  + $model->getMenuDropDownList($model->type_menu)) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'submenu_code')->textInput() ?>

    <?= $form->field($model, 'target')->dropDownList([
        ''=>'', '_self'=>'_self', '_blank'=>'_blank', '_parent'=>'_parent', '_top'=>'_top',
    ]) ?>

    <?= $form->field($model, 'blocked')->checkbox() ?>

    <?= $form->field($model, 'sort_index')->textInput() ?>

    <?= $form->field($model, 'key_name')->textInput(['maxlength' => true]) ?>

    <div class="btn-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
