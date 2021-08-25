<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use kartik\widgets\FileInput;

/** @var yii\web\View $this */
/** @var app\models\rating\RatingData $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="rating-data-form">

    <?php $form = ActiveForm::begin([
        'options'=> ['enctype' => 'multipart/form-data', 'autocomplete'=>'off'],
    ]); ?>

    <?= $form->field($model, 'rating_year')->dropDownList($model->getYears()) ?>

    <?= $form->field($model, 'rating_period')->dropDownList($model->getPeriods()) ?>

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
                ],
            ]) ?>
            <?php if (!$model->isNewRecord && count($model->getCheckListBoxUploadFiles())): ?>
                <hr />
                <?= $form->field($model, 'deleteFiles', [])
                    ->checkboxList($model->getCheckListBoxUploadFiles(), [
                        'item' => function($index, $label, $name, $checked, $value) {
                            return "<div class=\"checkbox\"><label><input type=\"checkbox\" name=\"{$name}\" value=\"{$value}\"> " . basename($label) . " "
                                . Html::a('(просмотр)', \Yii::$app->storage->getFileUrl($label), ['target' => '_blank']) . "</label></div>";
                        },
                    ]) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
