<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\conference\AbstractConference $model */
/** @var mixed $formData */
/** @var ActiveForm $form */

?>

<div class="conference-form border-bottom mb-2">    
    <div class="card">
        <div class="card-body">
            <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off', 'class' => 'mv-form']]); ?>
            <div class="form-group">
                <?= $form->errorSummary($formData) ?>        
                <?= $form->field($formData, 'result')->radioList([
                    1 => '<span class="text-success"><i class="fas fa-thumbs-up"></i> Согласовать</span>',
                    0 => '<span class="text-danger"><i class="fas fa-thumbs-down"></i> Отказать</span>',
                ], ['encode' => false])->label(false) ?>

                <?= $form->field($formData, 'denied_message')->textarea(['row'=>4])->label('Причина отказа') ?>
            </div>
            <?= Html::submitButton('Сохранить', ['class'=>'btn btn-primary']) ?>        
        </div>    
    </div>
    <?php ActiveForm::end() ?>
</div>

<?= $this->renderAjax('@app/views/conference/view', ['model'=>$model]) ?>

<?php 
$idFieldResult = Html::getInputId($formData, 'result');
$idFieldDeniedMessage = Html::getInputId($formData, 'denied_message');
$this->registerJs(<<<JS
    
    function showHideDeniedMessage() {
        $('#$idFieldDeniedMessage').parent('div').toggle($('#$idFieldResult [type="radio"][value="0"]').is(':checked'));
    }

    showHideDeniedMessage();
    
    $('#$idFieldResult').on('change', function() {
        showHideDeniedMessage();
    });
    
    /*
    
    */


JS);
?>