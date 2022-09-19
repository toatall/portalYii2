<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use kartik\widgets\FileInput;

/** @var yii\web\View $this */
/** @var app\models\rating\RatingMain $model */
/** @var yii\widgets\ActiveForm $form */
/** @var app\models\Tree $modelTree */

?>

<div class="rating-main-form">

    <div class="card card-body">

        <?php $form = ActiveForm::begin([
            'options'=> ['enctype' => 'multipart/form-data', 'autocomplete'=>'off'],
        ]); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'order_asc')->checkbox([
            'template' => '<div class="form-check form-switch">{input} {label}</div><div>{error}</div>',
        ]) ?>

        <?= $form->field($model, 'note')->textarea(['rows' => 5]) ?>

        <div class="card">
            <div class="card-header">Загрузка файлов</div>
            <div class="card-body">
                <?= $form->field($model, 'uploadFiles[]')->widget(FileInput::class, [
                    'options' => [
                        'accept' => 'files/*',
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'showUpload' => false,
                        'showPreview' => false,
                        'theme' => 'fa5',
                    ],
                ]) ?>
                <?php if (!$model->isNewRecord && count($model->getCheckListBoxUploadFilesGallery())): ?>
                    <hr />
                    <?= $form->field($model, 'deleteFiles', [])
                        ->checkboxList($model->getCheckListBoxUploadFilesGallery(), [
                            'item' => function($index, $label, $name, $checked, $value) {
                                return "<div class=\"checkbox\"><label><input type=\"checkbox\" name=\"{$name}\" value=\"{$value}\"> " . basename($label) . " "
                                    . Html::a('(просмотр)', \Yii::$app->storage->getFileUrl($label), ['target' => '_blank']) . "</label></div>";
                            },
                        ]) ?>
                <?php endif; ?>
            </div>
        </div>

        <hr />

        <div class="btn-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Отмена', ['index', 'idTree' => $modelTree->id], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
