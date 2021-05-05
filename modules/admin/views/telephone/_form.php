<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Telephone */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="telephone-form">

    <?php $form = ActiveForm::begin([
        'options'=> ['enctype' => 'multipart/form-data', 'autocomplete'=>'off'],
    ]); ?>

    <?= $form->field($model, 'id_organization')->dropDownList(ArrayHelper::map($model->getOrganizations(), 'code', 'name')) ?>

    <div class="panel panel-default">
        <div class="panel-heading"><?= $model->getAttributeLabel('telephone_file') ?></div>
        <div class="panel-body">
            <?php if (!$model->isNewRecord && $model->telephone_file): ?>
                Загружено: <?= Html::a('<i class="fas fa-image"></i> ' . basename($model->telephone_file), $model->telephone_file, ['target' => '_blank']) ?>
                <hr />
            <?php endif; ?>
            <?= $form->field($model, 'uploadFile')->fileInput()->label('Загрузить') ?>
        </div>
    </div>

    <?= $form->field($model, 'dop_text')->textarea(['rows' => 5]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
