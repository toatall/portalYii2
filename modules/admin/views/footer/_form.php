<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\admin\models\FooterType $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="footer-type-form">

    <?php $form = ActiveForm::begin([
        'id' => 'admin-footer-form',
        // 'enableClientValidation' => false,
        // 'enableAjaxValidation' => false,
        'validateOnSubmit' => false,
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
   
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php $this->registerJs(<<<JS
    // const modalId = $('#admin-footer-form').parents('div.modal')
    // if (modalId != undefined) {
    //     $(modalId).on('hide.bs.modal', function() {
    //         console.log('close modal...')
    //         delete modalId
    //     })
    // }
    // $('#admin-footer-form').on('beforeValidateAttribute', function(event, attribute) {
    //     return false
    // })
JS); ?>
