<?php

/** @var yii\web\View $this */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

?>

<?php $form = ActiveForm::begin([
    'id' => 'form-tasks',
    'options' => [
        'class' => 'mv-form',
    ],
]); ?>

    <?= Html::errorSummary($model, ['class' => 'alert alert-danger']) ?>

    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'count_tasks')->textInput() ?>
    <?= $form->field($model, 'finish_tasks')->textInput() ?>

    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>

<?php $this->registerJs(<<<JS
    
JS);