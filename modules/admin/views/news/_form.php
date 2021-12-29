<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use kartik\widgets\DatePicker;
use kartik\widgets\FileInput;
use kartik\widgets\TypeaheadBasic;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\news\News $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin([
        'options'=> ['enctype' => 'multipart/form-data', 'autocomplete'=>'off'],
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'message1')->textArea(['rows' => 5]) ?>

<?php $this->registerCss(<<<CSS
    .font-20px, .font-20px p {
        font-size: 20px;
    }
CSS); ?>
    <div class="font-20px">
    <?= $form->field($model, 'message2')->widget(CKEditor::class, [
        'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
            'preset' => 'full',
            'fontSize_sizes' => '20/20px;22/22px;24/24px;26/26px;28/28px;36/36px;48/48px;72/72px',
        ]),        
    ]) ?>
    </div>

    <div class="card">
        <div class="card-header"><?= $model->getAttributeLabel('thumbail_image') ?></div>
        <div class="card-body">
            <?php if (!$model->isNewRecord && $model->thumbail_image): ?>
            Загружено: <?= Html::a('<i class="fas fa-image"></i> ' . basename($model->thumbail_image), $model->thumbail_image, ['target' => '_blank']) ?>
            <hr />
            <?= $form->field($model, 'deleteThumbnailImage')->checkbox() ?>
            <hr />
            <?php endif; ?>
            <?= $form->field($model, 'uploadThumbnailImage')->fileInput()->label('Загрузить') ?>
        </div>
    </div>

    <?= $form->field($model, 'date_start_pub')->widget(DatePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'todayBtn' => true,
            'autoclose' => true,
            'startDate' => date('d.m.Y'),
        ],
    ]) ?>

    <?= $form->field($model, 'date_end_pub')->widget(DatePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'todayBtn' => true,
            'autoclose' => true,
            'startDate' => date('d.m.Y'),
        ],
    ]) ?>

    <?= $form->field($model, 'date_top')->widget(DatePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'todayBtn' => true,
            'autoclose' => true,
            'startDate' => date('d.m.Y'),
        ],
    ]) ?>

    <div class="panel panel-default">
        <div class="panel-heading">Загрузка файлов</div>
        <div class="panel-body">
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

    <div class="card">
        <div class="card-header">Загрузка изображений</div>
        <div class="card-body">
            <?= $form->field($model, 'uploadImages[]')->widget(FileInput::class, [
                'options' => [
                    'accept' => 'images/*',
                    'multiple' => true,
                ],
                'pluginOptions' => [
                    'showUpload' => false,
                ],
            ]) ?>
            <?php if (!$model->isNewRecord && count($model->getCheckListBoxUploadImagesGallery())): ?>
                <hr />
                <?= $form->field($model, 'deleteImages', [])
                    ->checkboxList($model->getCheckListBoxUploadImagesGallery(), [
                        'item' => function($index, $label, $name, $checked, $value) {
                            return "<div class=\"checkbox\"><label><input type=\"checkbox\" name=\"{$name}\" value=\"{$value}\"> " . basename($label) . " "
                                . Html::a('(просмотр)', \Yii::$app->storage->getFileUrl($label), ['target' => '_blank']) . "</label></div>";
                        },
                    ]) ?>
            <?php endif; ?>
        </div>
    </div>

    <?= $form->field($model, 'flag_enable')->checkbox() ?>

    <?= $form->field($model, 'on_general_page')->checkbox() ?>

    <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'from_department')
        ->widget(TypeaheadBasic::class, [
            'data' => ArrayHelper::map(\app\models\news\News::find()->where([
                'id_organization' => Yii::$app->userInfo->current_organization,
            ])->select('from_department')->orderBy('from_department')->groupBy('from_department')->all(), 'from_department', 'from_department'),
            'pluginOptions' => [
                'highlight' => true,
            ],
        ])
        //->dropDownList(['' => ''] + ArrayHelper::map(Department::find()->all(), 'department_name', 'department_name')) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php $this->registerJS(<<<JS
    // разрешить все
    CKEDITOR.config.allowedContent = true; 
JS
);
?>