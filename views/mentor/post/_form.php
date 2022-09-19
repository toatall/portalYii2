<?php

/** @var yii\web\View $this */
/** @var \app\models\mentor\MentorPost $model */
/** @var yii\widgets\ActiveForm $form */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use kartik\widgets\FileInput;


?>

<div class="news-form">

    <?php $form = ActiveForm::begin([
        'options'=> ['enctype' => 'multipart/form-data', 'autocomplete'=>'off'],
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'message1')->widget(CKEditor::class, [
        'editorOptions' => ElFinder::ckeditorOptions('elfinder', [
            'preset' => 'full',
        ]),
    ]) ?>

    <div class="card mb-2">
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

    <div class="btn-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Назад', ['mentor/way', 'id'=>$model->id_mentor_ways], ['class'=>'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php $this->registerJS(<<<JS
    // разрешить все
    CKEDITOR.config.allowedContent = true; 
JS
);
?>