<?php

use app\models\User;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\modules\contest\modules\pets\models\Pets $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pets-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'pet_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pet_age')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pet_owner')->widget(Select2::class, [
        'data' => ArrayHelper::map(User::find()->where(['user_disabled_ad' => false])->all(), 'username', 'concat'),
    ]) ?>


    <div class="card mt-3">
        <div class="card-header fw-bold">Загрузка файлов</div>
        <div class="card-body">
            <?= $form->field($model, 'uploadFiles[]')->widget(FileInput::class, [
                'options' => [
                    'accept' => 'images/*',
                    'multiple' => true,
                ],
                'pluginOptions' => [
                    'showUpload' => true,
                    'showPreview' => true,
                    'theme' => 'fa5',
                ],
            ]) ?>
            <?php if (!$model->isNewRecord && count($files = $model->getFiles())): ?>
                <hr />
                <div class="card card-body">
                    <?= $form->field($model, 'deleteFiles', [])
                        ->checkboxList($files, [
                            'item' => function($index, $label, $name, $checked, $value) {
                                return "<div class=\"checkbox\"><label><input type=\"checkbox\" name=\"{$name}\" value=\"" . $label . "\"> " . basename($label) . " "
                                    . Html::a('(просмотр)', \Yii::$app->storage->getFileUrl($label), ['target' => '_blank']) . "</label></div>";
                            },
                        ]) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="btn-group mt-3">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
