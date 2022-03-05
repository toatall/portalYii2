<?php

use app\modules\bookshelf\models\BookShelfPlace;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookShelf $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="book-shelf-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'mv-form',
        ],
    ]); ?>

    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'writer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>    

    <?= $form->field($model, 'place')->widget(Select2::class, [
        'data' => ArrayHelper::map(BookShelfPlace::find()->all(), 'place', 'place'),
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

    <?= $form->field($model, 'description')->textarea(['rows' => 7]) ?>

    <?= $form->field($model, 'date_received')->widget(DatePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'todayBtn' => true,
            'autoclose' => true,
        ],
    ]) ?>

    <?= ''/*$form->field($model, 'date_away')->widget(DatePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'todayBtn' => true,
            'autoclose' => true,            
        ],
    ])*/ ?>

    <?= $form->field($model, 'book_status')->widget(Select2::class, [
        'data' => $model->getStatuses(),
    ]) ?>
    
    <div class="form-group border-top pt-2">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
