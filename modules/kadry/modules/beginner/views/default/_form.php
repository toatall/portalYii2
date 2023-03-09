<?php

use app\models\department\Department;
use kartik\date\DatePicker;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\modules\beginner\models\Beginner $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="beginner-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal', 'class' => 'mv-form']); ?>

    <?= $form->field($model, 'id_department')->widget(Select2::class, [
        'data' => ['' => '- Выберите отдел -'] + Department::dropDownList(),
    ]) ?>

    <?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_employment')->widget(DatePicker::class, [
        'pluginOptions' => [           
            'todayHighlight' => true,
            'autoclose' => true,
        ],
    ]) ?>
   
    <?= $form->field($model, 'description')->widget(CKEditor::class, [
        'editorOptions' => ElFinder::ckeditorOptions('elfinder', [            
            'fontSize_sizes' => '20/20px;22/22px;24/24px;26/26px;28/28px;36/36px;48/48px;72/72px',            
        ]),        
    ]) ?>

        <?php /*
    <div class="card offset-2">
        <div class="card-header">
            <?= $model->getAttributeLabel('thumbImage') ?>
        </div>
        <div class="card-body">
            <?php if (!$model->isNewRecord && $model->thumbImage): ?>
                Загружено: <?= Html::a('<i class="fas fa-image"></i> ' 
                    . basename($model->thumbImage), $model->thumbImage, ['target' => '_blank']) ?>
                <hr />
                <?= $form->field($model, 'thumbDelete')->checkbox() ?>
                <hr />
            <?php endif; ?>
            <?= $form->field($model, 'thumb')->fileInput()->label('Загрузить') ?>
        </div>
    </div>


    <div class="card">
        <div class="card-header">
            Загрузка файлов
        </div>
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
        </div>
    </div>
    */ ?>
    

    <hr />
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
