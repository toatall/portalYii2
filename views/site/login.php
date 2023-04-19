<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login container">
    <div class="row">
        <div class="col-6 offset-3 border rounded p-5">
            <h1 class="display-3"><?= Html::encode($this->title) ?></h1>

            <p class="lead">Пожалуйста, заполните следующие поля для входа:</p>
            <hr />
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',            
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                    'inputOptions' => ['class' => 'col-lg-3 form-control'],
                    'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                ],
            ]); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox([]) ?>

                <div class="form-group">
                    <div class="col-lg-11">
                        <?= Html::submitButton('Вход', ['class' => 'btn btn-primary w-25', 'name' => 'login-button']) ?>
                    </div>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>    
       
</div>
