<?php

use kartik\range\RangeInput;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\department\DepartmentCard $model */
/** @var app\models\department\Department $modelDepartment */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="department-card-form">

    <div class="card card-body">

        <?php $form = ActiveForm::begin([
            'options' => [
                'class' => 'mv-form',
            ],
        ]); ?>

        <div class="row">

            <div class="col-6">
                <?= $form->field($model, 'user_fio')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'user_rank')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-6">
                <?= $form->field($model, 'user_position')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-6">
                <?= $form->field($model, 'user_telephone')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-12">
                <?= $form->field($model, 'user_resp')->textarea(['rows' => 5]) ?>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header"><?= $model->getAttributeLabel('photoFile') ?></div>
                    <div class="card-body">
                        <?php if (!$model->isNewRecord && $model->user_photo): ?>
                        <?= Html::img($model->user_photo, ['class'=>'img-thumbnail', 'style'=>'max-height: 10rem']) ?>
                        <?= $form->field($model, 'deletePhotoFile')->checkbox() ?>
                        <hr />
                        <?php endif; ?>
                        <?= $form->field($model, 'photoFile')->fileInput()->label('Загрузить') ?>
                    </div>
                </div>
            </div>
            
            <?= $form->field($model, 'user_level')->widget(RangeInput::class, [
                'options' => ['readonly' => true],
                'html5Options' => ['min' => 0, 'max' => 10],
                'html5Container' => ['style' => 'width: 30rem;'],
            ])->label($model->getAttributeLabel('user_level')) ?>

            <hr />

            <div class="col-12">
                <div class="form-group btn-group">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                    <?php if (!Yii::$app->request->isAjax): ?>
                        <?= Html::a('Назад', ['index', 'idDepartment'=>$modelDepartment->id], ['class' => 'btn btn-secondary']) ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
