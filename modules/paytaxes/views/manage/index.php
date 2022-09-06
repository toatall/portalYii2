<?php

/** @var \yii\web\View $this */

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap4\Html;


?>

<?= Html::beginForm('/paytaxes/manage/admin-data', 'get', ['id' => 'form-pay-taxes-admin']) ?>

<div class="row border-bottom pb-3">
    <div class="col-3">
        <?= Select2::widget([
            'name' => 'type',
            'pluginOptions' => [
                'placeholder' => 'Выберите вариант заполнения',
            ],
            'data' => [
                '1' => 'Главная',
                '2' => 'По дням',
                '3' => 'По месяцам',
            ], 
            'options' => [
                'id' => 'form-pay-taxes-admin-type'
            ],          
        ]) ?>
    </div>
    <div class="col-3" id="div-date" style="display: none;">
        <?= DatePicker::widget([
            'name' => 'date',
            'pluginOptions' => [
                'todayHighlight' => true,
                'todayBtn' => true,
                'autoclose' => true,   
                'placeholder' => 'Дата',             
            ],           
            'options' => [
                'autocomplete' => 'off',
            ],
        ]) ?>
    </div>
    <div class="col-3" id="div-month" style="display: none;">
        <?= Select2::widget([
            'name' => 'month',
            'data' => [
                'Январь' => 'январь',
                'Фвраль' => 'февраль',
                'Март' => 'март',
                'Апрель' => 'апрель',
                'Май' => 'май',
                'Июнь' => 'июнь',
                'Июль' => 'июль',
                'Август' => 'август',
                'Сентябрь' => 'сентябрь',
                'Октябрь' => 'октябрь',
                'Ноябрь' => 'ноябрь',
                'Декабрь' => 'декабрь',
            ],
            'pluginOptions' => [
                'placeholder' => 'Выберите месяц',
            ],
            'options' => [
                'id' => 'form-pay-taxes-admin-month'
            ],    
        ]) ?>
    </div>
    <div class="col">
        <?= Html::submitButton('Показать', ['class' => 'btn btn-primary']) ?>
    </div>    
</div>

<?= Html::endForm() ?>


<div class="row mt-3">
    <div class="col" id="div-result"></div>
</div>



<?php $this->registerJs(<<<JS
    
    $('select[name="type"]').on('change', function() {
        $('#div-date').toggle(
            $(this).val() == 1 || $(this).val() == 2
        );
        $('#div-month').toggle(
            $(this).val() == 3
        );
    });

    $('#form-pay-taxes-admin').on('submit', function() {
        const t = $(this).find('[name="type"]').val();
        const d = $(this).find('[name="date"]').val();
        const m = $(this).find('[name="month"]').val();
        const cont = $('#div-result');
        
        if (t == '' || (((t == 1) || (t == 2)) && d == '') || ((t == 3) && m == '')) {
            return false;
        }

        cont.html('<div class="d-flex justify-content-center">'
                + '<div class="spinner-border" role="status"><span class="visually-hidden"></span></div>'
                + '</div>');
        
        const url = $(this).attr('action');
        const data = $(this).serialize();
        $.ajax({
            url: url,
            method: 'get',
            data: data
        })
        .done(function(data) {
            cont.html(data);
        })
        .fail(function(err) {
            cont.html('<div class="text-danger">' + err.responseText + '</div>');
        });
        
        return false;
    });

JS); ?>