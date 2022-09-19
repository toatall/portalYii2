<?php

use kartik\date\DatePicker;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookShelfCalendar $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="book-shelf-calendar-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'mv-form',
        ],
    ]); ?>

    <?= $form->field($model, 'writer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_birthday')->widget(DatePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'todayBtn' => true,
            'autoclose' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'date_die')->widget(DatePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'todayBtn' => true,
            'autoclose' => true,
        ],
    ]) ?>    

    <div class="card mb-2">
        <div class="card-header"><?= $model->getAttributeLabel('photo') ?></div>
        <div class="card-body">
            <?php if (!$model->isNewRecord && $model->photo): ?>
                Загружено: <?= Html::a('<i class="fas fa-image"></i> ' . basename($model->photo), $model->photo, ['target' => '_blank']) ?>
                <hr />
                <?= $form->field($model, 'deletePhoto')->checkbox() ?>
                <hr />
            <?php endif; ?>
            <?= $form->field($model, 'uploadPhoto')->fileInput()->label('Загрузить') ?>
        </div>
    </div>

    <?= $form->field($model, 'description')->textarea(['rows' => 8]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
