<?php
/** @var yii\web\View $this */
/** @var app\models\lifehack\Lifehack $model */

use app\models\lifehack\LifehackTags;
use app\models\Organization;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\widgets\Pjax;

?>

<?php Pjax::begin(['id'=>'pjax-lifehack-index-form', 'timeout'=>false, 'enablePushState'=>false]) ?>

<?php $form = ActiveForm::begin([
    'options' => [
        'data-pjax' => true,
        'enctype' => 'multipart/form-data',
    ],
]); ?>

<?= $form->errorSummary($model) ?>

<?php if (Yii::$app->user->can('admin')): ?>
    <?= $form->field($model, 'org_code')->widget(Select2::class, [
        'data' => Organization::getDropDownList(),
    ]) ?>
<?php endif; ?>

<?= $form->field($model, 'tagsArray')->widget(Select2::class, [
    'data' => LifehackTags::getDropDownList(),
    'options' => ['multiple' => true],
    'pluginOptions' => [
        'allowClear' => true,
        //'tags' => true,
        'tokenSeparators' => [',', ';', '/'],
    ], 
]) ?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'text')->textarea(['rows' => 4]) ?>

<?= $form->field($model, 'author_name')->textInput(['maxlength' => true]) ?>

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
        <?php if (!$model->isNewRecord && count($model->getUploadedFiles())): ?>
            <hr />
            <?= $form->field($model, 'deleteFiles', [])
                ->checkboxList($model->getUploadedFiles(), [
                    'item' => function($index, $label, $name, $checked, $value) {
                        return "<div class=\"checkbox\"><label><input type=\"checkbox\" name=\"{$name}\" value=\"{$value}\"> " . basename($label) . " "
                            . Html::a('(просмотр)', \Yii::$app->storage->getFileUrl($label), ['target' => '_blank']) . "</label></div>";
                    },
                ]) ?>
        <?php endif; ?>
    </div>
</div>

<div class="btn-group pt-2">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    <?= Html::a('Отмена', ['lifehack/index'], ['class' => 'btn btn-secondary', 'pjax' => 1]) ?>
</div>

<?php ActiveForm::end(); ?>

<?php Pjax::end(); ?>


