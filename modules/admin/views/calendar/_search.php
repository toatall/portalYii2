<?php

use kartik\widgets\DatePicker;
use yii\bootstrap4\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CalendarResourceSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>
<div class="d-flex justify-content-center">
    <div class="calendar-search col-4 border rounded mb-2">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'id' => 'form-search-calendar',
            'options' => [
                'data-pjax' => 1,
            ],
        ]); ?>
        
        <?= $form->field($model, 'searchMonth')->widget(DatePicker::class, [
            'type' => DatePicker::TYPE_INLINE,
            'pluginOptions' => [
                'format' => 'dd.mm.yyyy',
                'startView' => 'months',
                'minViewMode' => 'months',
            ],        
            'options' => [
                'class' => 'border',
            ]
        ])->label(false) ?>
    <?php 
    $inputDate = Html::getInputName($model, 'searchMonth');
    $this->registerJs(<<<JS
        $('input[name="$inputDate"]').hide();
        $('input[name="$inputDate"]').on('change', function() {
            $('#form-search-calendar').submit();
        });
    JS); 
    $this->registerCss(<<<CSS
        .calendar-search .datepicker-inline {
            width: 100%;
        }
        .calendar-search .datepicker table {
            width: 100%;
        }
    CSS);

    ?>       
        <?php ActiveForm::end(); ?>

    </div>
</div>