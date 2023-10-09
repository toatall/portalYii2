<?php

use app\models\Organization;
use app\widgets\CodeEditorWidget;
use kartik\date\DatePicker;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\helpers\Url;
use kartik\widgets\DepDrop;

/** @var yii\web\View $this */
/** @var app\modules\kadry\modules\beginner\models\Beginner $model */
/** @var yii\widgets\ActiveForm $form */

$url = Url::to(['/department/json-list-by-org', 'selected' => $model->id_department]);
?>

<div class="beginner-form">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal', 'class' => 'mv-form']); ?>

    <?php if (Yii::$app->user->can('admin')): ?>
        <?= $form->field($model, 'org_code')->widget(Select2::class, [
            'data' => Organization::getDropDownList(),
        ]) ?>
    <?php else: ?>
        <?= $form->field($model, 'org_code')->hiddenInput()->label(false) ?>
    <?php endif; ?>
    
    <?= $form->field($model, 'id_department')->widget(DepDrop::class, [
        'pluginOptions' => [
            'depends' => [Html::getInputId($model, 'org_code')],
            'initialize' => true,
            'initDepends' => [Html::getInputId($model, 'org_code')],
            'url' => $url,
            'placeholder' => 'Выберите отдел...',
        ],
    ]) ?>

    <?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_employment')->widget(DatePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'autoclose' => true,
        ],
        'options' => [
            'autocomplete' => 'off',
        ],
    ]) ?>

    <?= $form->field($model, 'description')->widget(CKEditor::class, [
        'editorOptions' => ElFinder::ckeditorOptions('elfinder', [            
            'fontSize_sizes' => '20/20px;22/22px;24/24px;26/26px;28/28px;36/36px;48/48px;72/72px',            
        ]),        
    ]) ?>

    <div class="card offset-2">
        <div class="card-header">
            <?= $model->getAttributeLabel('thumbUpload') ?>
        </div>
        <div class="card-body">
            <?php if (!$model->isNewRecord): ?>
                <?= app\widgets\FilesGallery\ImagesWidget::widget([
                    'containerTitle' => null,
                    'files' => $model->getThumbImage(),
                    'allowDelete' => true,
                    'deleteAction' => ['delete-files', 'id'=>$model->id],
                ]) ?>
            <?php endif; ?>
            <div class="mt-4">
                <?= $form->field($model, 'thumbUpload')->fileInput()->label('Загрузить') ?>
            </div>
        </div>
    </div>
    
    
    <div class="card offset-2 mt-3">
        <div class="card-header">
            <?= $model->getAttributeLabel('filesUpload') ?>
        </div>
        <div class="card-body">
            <?php if (!$model->isNewRecord): ?>
                <?= app\widgets\FilesGallery\ImagesWidget::widget([
                    'containerTitle' => null,
                    'files' => $model->getGalleryImages(),
                    'allowDelete' => true,
                    'deleteAction' => ['delete-files', 'id'=>$model->id],
                ]) ?>
            <?php endif; ?>
            <div class="mt-4">
                <?= $form->field($model, 'filesUpload[]')->widget(FileInput::class, [
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
    </div>
   

    <?php if (Yii::$app->user->can('admin')): ?>
        <div class="offset-2 mt-3">
            <?= CodeEditorWidget::widget([
                'id' => 'collapse_beginner',
                'items' => [                
                    'javascript' => [
                        'aceMode' => 'javascript', 
                        'aceModel' => $model, 
                        'aceAttribute' => 'js',
                    ],
                    'css' => [
                        'aceMode' => 'css', 
                        'aceModel' => $model, 
                        'aceAttribute' => 'css',
                    ],                    
                ],
            ]) ?>
        </div>
    <?php endif; ?>    
    <hr />
    <div class="btn-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
