<?php

use app\models\Organization;
use kartik\select2\Select2;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\executetasks\models\ExecuteTasksDescriptionOrganization $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="execute-tasks-description-organization-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',            
        ],
    ]); ?>

    <?= $form->field($model, 'code_org')->widget(Select2::class, [
        'data' => Organization::getDropDownList(),
        'pluginOptions' => [
            'placeholder' => 'Выберите организацию',
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

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
