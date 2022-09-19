<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use kartik\widgets\FileInput;

/** @var yii\web\View $this */
/** @var app\models\rating\RatingData $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="rating-data-form mt-3">

    <div class="card card-body">
        
        <?php $form = ActiveForm::begin([
            'options'=> ['enctype' => 'multipart/form-data', 'autocomplete'=>'off'],
        ]); ?>

        <div class="row">
            <div class="col">
                <?= $form->field($model, 'rating_year')->dropDownList($model->getYears()) ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'rating_period')->dropDownList($model->getPeriods()) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <?= $form->field($model, 'note')->textarea(['rows' => 5]) ?>
            </div>
            <div class="col-12">
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
            </div>
        </div>

        <hr />

        <div class="btn-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Отмена', ['index', 'idMain' => $model->id_rating_main], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    
    </div>

</div>
