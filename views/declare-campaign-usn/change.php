<?php

use app\models\DeclareCampaignUsn;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\DeclareCampaignUsn $model */

$this->title = 'Редактирование данных декларационной кампании УСН';
$this->params['breadcrumbs'][] = ['label' => 'Декларационная компания ' . date('Y') . ' года по УСН', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="declare-campaign-usn-create">

    <h1 class="title mv-hide">
        <?= Html::encode($this->title) ?>
    </h1>

    <div class="card card-body mb-3">
        <?= Html::beginForm(['/declare-campaign-usn/form'], 'post', ['id' => 'declare-campaign-usn-form-condition']) ?>
        <div class="row mb-3">
            <div class="col">
                <div class="form-group row">
                    <label class="col col-form-label text-end fw-bold">Отчетный год</label>
                    <div class="col">
                        <?= Select2::widget([
                            'id' => 'declare-capmaign-usn-years',
                            'name' => 'year',
                            'data' => DeclareCampaignUsn::getReportsYears(),
                            'value' => date('Y'),
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group row">
                    <label class="col col-form-label text-end fw-bold">Срок уплаты</label>
                    <div class="col">
                        <?= Select2::widget([
                            'id' => 'declare-capmaign-usn-deadline',
                            'name' => 'deadline',
                            'data' => DeclareCampaignUsn::getReportsDeadline(),                            
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group row">
                    <label class="col col-form-label text-end fw-bold">Отчетная дата</label>
                    <div class="col">
                        <?= DatePicker::widget([
                            'id' => 'declare-capmaign-usn-datepicker',
                            'name' => 'date',
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'todayBtn' => true,
                                'autoclose' => true,
                            ],
                            'options' => [
                                'autocomplete' => 'off',
                                'required' => 'true',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class="col-2 text-center">
                <?= Html::submitButton('Показать <span class="addon"></span>', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        
        <?= Html::endForm() ?>
        <?php $this->registerJs(<<<JS
            $('#declare-campaign-usn-form-condition').off('submit')
            $('#declare-campaign-usn-form-condition').on('submit', function() {
                const url = $(this).attr('action')
                const cont = $('#container-declare-campaign-usn-form')
                const btn = $(this).find('button[type="submit"]')
                                
                btn.prop('disabled', true)
                btn.find('.addon').html('<i class="fas fa-circle-notch fa-spin"></i>')
                $.ajax({
                    url: url,
                    method: 'post',
                    data: $(this).serialize()
                })
                .done(function(data) {
                    cont.html(data);
                })
                .fail(function(jqXHR) {
                    cont.html('<div class="alert alert-danger">' + jqXHR.status + ' ' + jqXHR.statusText + '</div>');
                })
                .always(function() {
                    btn.prop('disabled', false)
                    btn.find('.addon').html('')
                })

                return false
            })
        JS); ?>
    </div>

    <div id="container-declare-campaign-usn-form"></div> 

</div>
