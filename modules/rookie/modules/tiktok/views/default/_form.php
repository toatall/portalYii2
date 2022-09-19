<?php

use app\models\department\Department;
use app\modules\rookie\modules\tiktok\models\Tiktok;
use kartik\select2\Select2;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\rookie\modules\tiktok\models\Tiktok $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tiktok-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'mv-form',
        ],
    ]); ?>

    <?= $form->field($model, 'department_id')->widget(Select2::class, [
        'data' => Department::dropDownList(),
        'pluginOptions' => [
            'placeholder' => 'Select department',
        ],
    ]) ?>

    <?= $form->field($model, 'filename')->widget(Select2::class, [
        'data' => Tiktok::getVideos(),
        'pluginOptions' => [
            'placeholder' => 'Select video'
        ],
    ]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <hr />
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
