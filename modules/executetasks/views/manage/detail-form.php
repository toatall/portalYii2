<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\executetasks\models\ExecuteTasksDetail $model */


?>
<div class="execute-tasks-detail-create">

    <?php $form = ActiveForm::begin([
        'id' => 'form-tasks',
        'options' => [
            'class' => 'mv-form',
        ],
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
    ]); ?>

    <?= Html::errorSummary($model, ['class' => 'alert alert-danger']) ?>

    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'count_tasks')->textInput() ?>
    <?= $form->field($model, 'finish_tasks')->textInput() ?>

    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end(); ?>

</div>
