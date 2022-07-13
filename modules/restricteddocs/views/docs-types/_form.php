<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\restricteddocs\models\RestrictedDocsTypes $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="restricted-docs-types-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'data-pjax' => true,
        ],
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>    

    <div class="btn-group mt-3">
        <?= Html::a('Назад', ['/restricteddocs/docs-orgs/index'], ['class' => 'btn btn-secondary']) ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
