<?php

use kartik\typeahead\Typeahead;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use kartik\widgets\DatePicker;
use kartik\widgets\FileInput;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\news\News $model */
/** @var yii\widgets\ActiveForm $form */
/** @var string $urlBack */

?>

<div class="news-form card card-body">

    <?php $form = ActiveForm::begin([
        'options'=> ['enctype' => 'multipart/form-data', 'autocomplete'=>'off'],
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'message1')->textarea() ?>

    <?= $form->field($model, 'message2')->widget(CKEditor::class, [
        'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
            //'preset' => 'full',
            'fontSize_sizes' => '20/20px;22/22px;24/24px;26/26px;28/28px;36/36px;48/48px;72/72px',
            // 'contentsCss' => [' .cke_editable { font-size: 20px !important; }'],
            // 'bodyClass' => 'font-20px',     
        ]),        
    ]) ?>
    
    <div class="card">
        <div class="card-header">
            <?= $model->getAttributeLabel('thumbail_image') ?>
        </div>
        <div class="card-body">
            <?php if (!$model->isNewRecord && $model->thumbail_image): ?>
                Загружено: <?= Html::a('<i class="fas fa-image"></i> ' 
                    . basename($model->thumbail_image), $model->thumbail_image, ['target' => '_blank']) ?>
                <hr />
                <?= $form->field($model, 'deleteThumbnailImage')->checkbox() ?>
                <hr />
            <?php endif; ?>
            <?= $form->field($model, 'uploadThumbnailImage')->fileInput()->label('Загрузить') ?>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            <?= $form->field($model, 'date_start_pub')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'todayBtn' => true,
                    'autoclose' => true,
                    'startDate' => date('d.m.Y'),
                ],
            ]) ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'date_end_pub')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'todayBtn' => true,
                    'autoclose' => true,
                    'startDate' => date('d.m.Y'),
                ],
            ]) ?>            
        </div>
        <div class="col">
            <?= $form->field($model, 'date_top')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'todayBtn' => true,
                    'autoclose' => true,
                    'startDate' => date('d.m.Y'),
                ],
            ]) ?>            
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
            <?php if (!$model->isNewRecord && count($model->getCheckListBoxUploadFilesGallery())): ?>
                <hr />
                <?= $form->field($model, 'deleteFiles', [])
                    ->checkboxList($model->getCheckListBoxUploadFilesGallery(), [
                        'item' => function($index, $label, $name, $checked, $value) {
                            return "<div class=\"checkbox\"><label><input type=\"checkbox\" name=\"{$name}\" value=\"{$value}\"> " 
                                . '<span class="file">' . basename($label) . '</span>'
                                . (file_exists(Yii::getAlias('@webroot' . $label)) 
                                    ? ' (' . Yii::$app->storage->sizeText(Yii::$app->storage->size(Yii::getAlias('@webroot' . $label))) . ') '
                                    : ' ') 
                                . Html::a('(просмотр)', Url::to($label, true), ['target' => '_blank'])
                                . "</label></div>";
                                
                        },
                    ])->label(null, ['class'=>'fw-bold border-bottom mb-2']) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            Загрузка изображений
        </div>
        <div class="card-body">
            <?= $form->field($model, 'uploadImages[]')->widget(FileInput::class, [
                'options' => [
                    'accept' => 'images/*',
                    'multiple' => true,
                ],
                'pluginOptions' => [
                    'showUpload' => false,
                    'theme' => 'fa5',
                ],
            ]) ?>
            <?php if (!$model->isNewRecord && count($model->getCheckListBoxUploadImagesGallery())): ?>
                <hr />
                <?= $form->field($model, 'deleteImages', [])
                    ->checkboxList($model->getCheckListBoxUploadImagesGallery(), [
                        'item' => function($index, $label, $name, $checked, $value) {
                            return "<div class=\"checkbox\"><label><input type=\"checkbox\" name=\"{$name}\" value=\"{$value}\"> " 
                                . '<span class="image">' . basename($label) . '</span> '
                                . (file_exists(Yii::getAlias('@webroot'. $label))
                                    ? ' (' . Yii::$app->storage->sizeText(Yii::$app->storage->size(Yii::getAlias('@webroot' . $label))) . ') '
                                    : ' ')
                                . Html::a('(просмотр)', Url::to($label, true), ['target' => '_blank']) 
                                . "</label></div>";
                        },
                    ])->label(null, ['class'=>'fw-bold border-bottom mb-2']) ?>
            <?php endif; ?>
        </div>
    </div>

    <br />

    <?= $form->field($model, 'flag_enable')->checkbox([
        'template' => '<div class="form-check form-switch">{input} {label}</div><div>{error}</div>',
    ]) ?>

    <?= ''/*$form->field($model, 'on_general_page')->checkbox([
        'template' => '<div class="form-check form-switch">{input} {label}</div><div>{error}</div>',
    ])*/ ?>

    <?= $form->field($model, 'tags')->textInput() ?>

    <?= $form->field($model, 'from_department')
        ->widget(Typeahead::class, [
            'dataset' => [
                [
                    'prefetch' => Url::to(['/admin/news/ajax-departments-authors', 't' => time()]),
                    'limit' => 10,
                ],
            ],
            'pluginOptions' => [
                'highlight' => true,
            ],
        ]) ?>

    <div class="btn-group mt-3">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', $urlBack, ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php $this->registerJS(<<<JS
    // разрешить все
    CKEDITOR.config.allowedContent = true; 

    // подставить значки для файлов
    $('.file').each(function() {
        let a = $(this).text().toLowerCase().split('.');
        let icon = 'far fa-file';
        if (a[1] != null) {            
            switch (a[a.length - 1]) {
                case 'pdf':
                    icon = 'far fa-file-pdf';
                    break;
                case 'doc':
                case 'docx':
                    icon = 'far fa-file-word';
                    break;
                case 'xls':
                case 'xlsx':
                    icon = 'far fa-file-excel';
                    break;
                case 'jpg':
                case 'jpeg':
                case 'bmp':
                case 'gif':
                    icon = 'far fa-image';
                    break;
            }            
        }
        $(this).html('<i class="' + icon + '"></i> ' + $(this).html());
    });

    $('.image').each(function() {
        $(this).html('<i class="far fa-image"></i> ' + $(this).html());
    });

JS
);
?>
