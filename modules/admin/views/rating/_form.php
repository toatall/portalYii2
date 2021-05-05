<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\rating\RatingMain */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="rating-main-form">

    <?php $form = ActiveForm::begin([
        'options'=> ['enctype' => 'multipart/form-data', 'autocomplete'=>'off'],
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_asc')->checkbox() ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 5]) ?>

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

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
