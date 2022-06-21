<?php

use app\models\department\Department;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\executetasks\models\ExecuteTasksDepartment $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="execute-tasks-department-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',            
        ],
    ]); ?>

    <?= $form->field($model, 'id_department')->widget(Select2::class, [
        'data' => Department::dropDownList(),
        'pluginOptions' => [
            'placeholder' => 'Выберите отдел',
        ],
    ]) ?>

    <div class="card mb-3">
        <div class="card-header">Фото</div>
        <div class="card-body">
            <?= $form->field($model, 'uploadImage')->fileInput()->label(false) ?>
            <?php if (!$model->isNewRecord && ($img = $model->getImage()) != null): ?>
                <hr />
                <?= Html::img($img, ['style' => 'width: 10rem;', 'class' => 'img-thumbnail']) ?><br />
                <?= $form->field($model, 'deleteImage')->checkbox() ?>
            <?php endif; ?>
        </div>
    </div>

    <?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'post')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rank')->textInput(['rank' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 8]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>