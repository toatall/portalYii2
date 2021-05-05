<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use app\models\Organization;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password1')->passwordInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'password2')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'username_windows')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'default_organization')->dropDownList(Organization::getDropDownList()) ?>

    <?= $form->field($model, 'current_organization')->dropDownList(Organization::getDropDownList()) ?>

    <?= $form->field($model, 'blocked')->checkbox() ?>

    <?= $form->field($model, 'folder_path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'post')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rank')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'about')->textarea(['rows'=>'5']) ?>

    <?= $form->field($model, 'department')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'roles')->checkboxList($model->getListRoles()) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
