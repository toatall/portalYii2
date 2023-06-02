<?php
/** @var \yii\web\View $this */
/** @var \app\modules\meeting\models\Locations $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
?>

<div class="meeting-location-form card">
    
    <?php $form = ActiveForm::begin([
        'options' => [
            'data-pjax' => true,
        ],
    ]); ?>
    <div class="card-body">
        <?= $form->errorSummary($model) ?>
        <?= $form->field($model, 'location') ?>
    </div>
    <div class="card-footer">
        <div class="btn-group mt-2">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-sm']) ?>
            <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary btn-sm']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
