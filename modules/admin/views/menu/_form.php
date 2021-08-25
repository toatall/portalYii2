<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Menu\Menu $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="card">
        <div class="card-header">
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
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
