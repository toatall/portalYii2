<?php
/** @var \yii\web\View $this */
/** @var \app\modules\admin\modules\grantaccess\models\GrantAccessGroupAdGroup $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
?>

<h2 class="title mv-hide">Группа ActiveDirectory</h2>

<?php $form = ActiveForm::begin([
    'options' => [
        'class' => 'mv-form',
    ],
]); ?>

<?= $form->errorSummary($model) ?>

<?= $form->field($model, 'group_name')->textInput(['maxlength' => true]) ?>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-sm']) ?>

<?php ActiveForm::end(); ?>
