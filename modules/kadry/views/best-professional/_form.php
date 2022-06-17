<?php

use app\models\Organization;
use app\modules\kadry\models\BestProfessional;
use kartik\select2\Select2;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\modules\kadry\models\BestProfessional $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="best-professional-form">

    <?php Pjax::begin(['timeout' => false, 'enablePushState' => false]) ?>

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data', 
            'autocomplete'=>'off',
            'data-pjax' => true,
        ],
        'id' => 'form-best-professional',
    ]); ?>

    <?php $form->errorSummary($model) ?>


    <div class="card">
        <div class="card-header">
            Отчетный период
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <?= $form->field($model, 'period')->widget(Select2::class, [
                        'data' => BestProfessional::periods(),
                        'options' => [
                            'placeholder' => $model->getAttributeLabel('period'),
                        ],
                    ])->label(false) ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'period_year')->widget(Select2::class, [
                        'data' => BestProfessional::periodsYears(),
                        'options' => [
                            'placeholder' => $model->getAttributeLabel('period_year'),
                        ],
                    ])->label(false) ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card mt-4">
        <div class="card-header">
            Информация о сотруднике
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col">
                    <?= $form->field($model, 'org_code')->widget(Select2::class, [
                        'data' => Organization::getDropDownList(),
                        'options' => [
                            'placeholder' => $model->getAttributeLabel('org_code'),
                        ],
                    ])->label(false) ?>    
                </div>
                <div class="col">
                    <?= $form->field($model, 'department')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('department')])->label(false) ?>
                </div>
            </div>

            <?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'nomination')->textInput(['maxlength' => true]) ?>

            <div class="font-20px">
                <?= $form->field($model, 'description')->widget(CKEditor::class, [
                    'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
                        //'preset' => '',
                        'fontSize_sizes' => '20/20px;22/22px;24/24px;26/26px;28/28px;36/36px;48/48px;72/72px',
                    ]),        
                ]) ?>
            </div>

            <div class="card mb-3">
                <div class="card-header">Изображение</div>
                <div class="card-body">
                    <?= $form->field($model, 'uploadImage')->fileInput()->label(false) ?>
                    <?php if (($img = $model->getImage()) != null): ?>
                        <hr />
                        <?= Html::img($img, ['style' => 'width: 10rem;', 'class' => 'img-thumbnail']) ?><br />
                        <?= $form->field($model, 'deleteImage')->checkbox() ?>
                    <?php endif; ?>
                </div>
            </div>
    
        </div>
    </div>

    <div class="form-group mt-4">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>


    <?php ActiveForm::end(); ?>

    <?php Pjax::end() ?>

</div>
