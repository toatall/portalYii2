<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\kadry\models\education\Education $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="education-form card card-body">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'description_full')->textarea(['rows' => 10]) ?>

    <div class="card">
        <div class="card-header"><?= $model->getAttributeLabel('thumbnail') ?></div>
        <div class="card-body">
            <?php if (!$model->isNewRecord && $model->thumbnail): ?>
            Загружено: <?= Html::a('<i class="fas fa-image"></i> ' . basename($model->thumbnail), $model->thumbnail, ['target' => '_blank']) ?>
            <hr />
            <?= $form->field($model, 'deleteThumbnailImage')->checkbox() ?>
            <hr />
            <?php endif; ?>
            <?= $form->field($model, 'uploadThumbnailImage')->fileInput()->label('Загрузить') ?>
        </div>
    </div>

    <?= $form->field($model, 'duration')->textInput(['maxlength' => true]) ?>    

    <div class="form-group btn-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['/kadry/education-admin/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
