<?php

/** @var \yii\web\View $this */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\widgets\Pjax;

/** @var array $data */
/** @var app\modules\paytaxes\models\PayTaxesGeneral[] $models */

?>

<div class="table-responsive">

    <?php Pjax::begin(['id' => 'pjax-pay-taxes-manage-general', 'timeout' => false, 'enablePushState' => false]) ?>
    
    <?php $form = ActiveForm::begin([
        'id' => 'form-pay-taxes-manage-general',
        'options' => [
            'data-pjax' => true,
        ],
    ]) ?>

    <div class="card mb-4">
        <div class="card-header">Массовый ввод</div>
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Формат ввода:</strong><br/>
                <small>
                    <code>
                [Код НО]  [Начисления (прогнозируемые), тыс. рублей]  [Поступления с 01.09.2021, тыс. рублей]  [КПЭ показатель (средний)]  [КПЭ показатель НИФЛ	КПЭ показатель транспортный налог]  [КПЭ показатель земельный налог	Оставшаяся сумма до 95 % (всего)]  [Оставшаяся сумма до 95 % (НИФЛ)]  [Оставшаяся сумма до 95 % (ТН)]  [Оставшаяся сумма до 95 % (ЗН)]  [Прирост КПЭ показателя с предыдущей даты]<br />
                ...<br />
                [Код НО]  [Начисления (прогнозируемые), тыс. рублей]  [Поступления с 01.09.2021, тыс. рублей]  [КПЭ показатель (средний)]  [КПЭ показатель НИФЛ	КПЭ показатель транспортный налог]  [КПЭ показатель земельный налог	Оставшаяся сумма до 95 % (всего)]  [Оставшаяся сумма до 95 % (НИФЛ)]  [Оставшаяся сумма до 95 % (ТН)]  [Оставшаяся сумма до 95 % (ЗН)]  [Прирост КПЭ показателя с предыдущей даты]<br />
                    </code>
                </small>
            </div>
            <div class="row">
                <div class="col-11">
                    <textarea id="bulk-text" rows="6" class="form-control"></textarea>
                </div>
                <div class="col">
                    <button id="btn-bulk" type="button" class="btn btn-warning">Заполнить</button>
                </div>
            </div>
        </div>
    </div>

    
    <?php $form->errorSummary($models) ?>

    <table class="table table-bordered">
        <tr>
            <th>Код НО</th>
            <th>Начисления</th>
            <th>Поступления с 01.09.2021</th>
            <!-- <th>Sum3</th> -->
            <th>КПЭ (средний)</th>
            <th>КПЭ (НИФЛ)</th>
            <th>КПЭ (ТП)</th>
            <th>КПЭ (ЗН)</th>
            <th>Оставшаяся сумма до 95 %(всего)</th>
            <th>Оставшаяся сумма до 95 % (НИФЛ)</th>
            <th>Оставшаяся сумма до 95 % (ТН)</th>
            <th>Оставшаяся сумма до 95 % (ЗН)</th>
            <th>Прирост КПЭ показателя с предыдущей даты</th>   
            <!-- <th>Достижение КПЭ (95 %)</th> -->
        </tr>
    <?php foreach($models as $org => $model): ?>
        <tr>
            <td>
                <?= $org ?>
                <?php if ($model->isNewRecord): ?>
                    <br /><span class="badge badge-secondary">Не сохранено</span>
                <?php else: ?>
                    <br /><span class="badge badge-success">Сохранено</span>
                <?php endif; ?>
                <?php if ($model->hasErrors()): ?>
                    <div class="alert alert-danger small">                    
                    <?php foreach($model->getErrors() as $errors): ?>
                        <?php foreach($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </td>
            <td><?= $form->field($model, "[$org]sum1")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$org]sum2")->textInput()->label(false) ?></td>
            <!-- <td><?= $form->field($model, "[$org]sum3")->textInput()->label(false) ?></td> -->
            <td><?= $form->field($model, "[$org]sms")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$org]sms_1")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$org]sms_2")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$org]sms_3")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$org]sum_left_all")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$org]sum_left_nifl")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$org]sum_left_tn")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$org]sum_left_zn")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$org]growth_sms")->textInput()->label(false) ?></td>
            <!-- <td><?= $form->field($model, "[$org]kpe_persent")->textInput()->label(false) ?></td> -->
        </tr>
    <?php endforeach; ?>
    </table>
   
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
   
    <?php $form->end() ?>
   
<?php 
$this->registerJs(<<<JS

    $('#btn-bulk').on('click', function() {
        let text = $('#bulk-text').val();
        const form = $('#form-pay-taxes-manage-general');
        text.split("\\n").forEach(function(valLine) {
            const vals = valLine.split("\t")
            const org = vals[0] ?? null
            const sum1 = vals[1] ?? null
            const sum2 = vals[2] ?? null
            const sms = vals[3] ?? null
            const sms_1 = vals[4] ?? null
            const sms_2 = vals[5] ?? null
            const sms_3 = vals[6] ?? null
            const sum_left_all = vals[7] ?? null
            const sum_left_nifl = vals[8] ?? null
            const sum_left_tn = vals[9] ?? null
            const sum_left_zn = vals[10] ?? null
            const growth_sms = vals[11] ?? null
            
            if (!org) {
                return
            }
            
            // подстановка чисел
            form.find('input[name="PayTaxesGeneral[' + org + '][sum1]"]').val(clearText(sum1))            
            form.find('input[name="PayTaxesGeneral[' + org + '][sum2]"]').val(clearText(sum2))
            form.find('input[name="PayTaxesGeneral[' + org + '][sms]"]').val(clearText(sms))
            form.find('input[name="PayTaxesGeneral[' + org + '][sms_1]"]').val(clearText(sms_1))
            form.find('input[name="PayTaxesGeneral[' + org + '][sms_2]"]').val(clearText(sms_2))
            form.find('input[name="PayTaxesGeneral[' + org + '][sms_3]"]').val(clearText(sms_3))
            form.find('input[name="PayTaxesGeneral[' + org + '][sum_left_all]"]').val(clearText(sum_left_all))
            form.find('input[name="PayTaxesGeneral[' + org + '][sum_left_nifl]"]').val(clearText(sum_left_nifl))
            form.find('input[name="PayTaxesGeneral[' + org + '][sum_left_tn]"]').val(clearText(sum_left_tn))
            form.find('input[name="PayTaxesGeneral[' + org + '][sum_left_zn]"]').val(clearText(sum_left_zn))
            form.find('input[name="PayTaxesGeneral[' + org + '][growth_sms]"]').val(clearText(growth_sms))
        })
    });

    $('#bulk-text').on('change', function() {
        $('#btn-bulk').trigger('click');
    })

    $('#bulk-text').on('keyup', function() {
        $('#btn-bulk').trigger('click');
    });

    // удаление пробелов
    function clearText(text) {
        if (text != null) {
            return text.replace(/[\s|\%]/g, '')
        }
        return text
    }

JS); ?>


    <?php Pjax::end() ?>
</div>
