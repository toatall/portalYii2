<?php

use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\DeclareCampaignUsn[] $models */
/** @var string $year */
/** @var string $date */
/** @var string $deadline */

$firstModel = reset($models);
?>

<div class="declare-campaign-usn-form">

    <div class="alert alert-secondary">
        <strong>Отчетный год</strong> <?= $year ?><br />
        <strong>Отчетная дата</strong> <?= $date ?><br />
        <strong>Срок уплаты</strong> <?= $deadline ?>
    </div>
    
    <?php Pjax::begin([
        'id' => 'pjax-declare-campaign-usn-form', 
        'enablePushState' => false, 
        'timeout' => false, 
        'clientOptions' => ['url' => Url::to(['form'])],
    ]) ?>

    <div class="mb-3">
        <?= Html::beginForm('', 'post', ['data-pjax' => true, 'autocomplete' => 'off']) ?>
            <?= Html::hiddenInput('year', $year) ?>
            <?= Html::hiddenInput('date', $date) ?>
            <?= Html::hiddenInput('deadline', $deadline) ?>
            <?= Html::hiddenInput('delete', true) ?>
            <?= Html::submitButton('<i class="fas fa-trash"></i> Очистить данные за ' . $date, [
                'class' => 'btn btn-danger btn-sm',
                'data' => ['confirm' => 'Вы уверены, что хотите очистить?']
            ]) ?>
        <?= Html::endForm() ?>
    </div>


    <?php $form = ActiveForm::begin([
        'id' => 'form-declare-campaign-usn',
        'options' => ['data-pjax' => true, 'autocomplete' => 'off'],
    ]); ?>

    <?= Html::hiddenInput('year', $year) ?>
    <?= Html::hiddenInput('date', $date) ?>
    <?= Html::hiddenInput('deadline', $deadline) ?>

    <div class="card mb-4">
        <div class="card-header">Массовый ввод</div>
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Формат ввода:</strong><br/>
                <small>
                    <code>
                [Код НО]  [Количество НП]  [Количество НП представивших верные Уведомления]  [Количество НП, которым Уведомление представлять не требуется]  [Сумма начисленного налога по Уведомлениям, руб.]<br />
                ...<br />
                [Код НО]  [Количество НП]  [Количество НП представивших верные Уведомления]  [Количество НП, которым Уведомление представлять не требуется]  [Сумма начисленного налога по Уведомлениям, руб.]<br />
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

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success mx-2 fw-bold"><i class="far fa-check-circle"></i> <?= Yii::$app->session->getFlash('success') ?></div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('danger')): ?>
        <div class="alert alert-danger mx-2 fw-bold"><i class="fas fa-exclamation-circle"></i> <?= Yii::$app->session->getFlash('danger') ?></div>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Код НО</th>
                <th><?= !empty($firstModel) ? $firstModel->getAttributeLabel('count_np') : '' ?></th>                
                <th><?= !empty($firstModel) ? $firstModel->getAttributeLabel('count_np_provides_reliabe_declare') : '' ?></th>
                <th><?= !empty($firstModel) ? $firstModel->getAttributeLabel('count_np_provides_not_required') : '' ?></th>
                <th><?= !empty($firstModel) ? $firstModel->getAttributeLabel('accrued_sum') : '' ?></th>
            </tr>
        </thead>
        
        <?php foreach($models as $code => $model): ?>    
        <tr>
            <td><?= $code ?></td>
            <td><?= $form->field($model, '[' . $code . ']count_np')->textInput()->label(false) ?></td>          
            <td><?= $form->field($model, '[' . $code . ']count_np_provides_reliabe_declare')->textInput()->label(false) ?></td>
            <td><?= $form->field($model, '[' . $code . ']count_np_provides_not_required')->textInput()->label(false) ?></td>
            <td><?= $form->field($model, '[' . $code . ']accrued_sum')->textInput()->label(false) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>        
    </div>

    <?php ActiveForm::end(); ?>

<?php 
$this->registerJs(<<<JS

    $('#btn-bulk').on('click', function() {
        let text = $('#bulk-text').val();
        const form = $('#form-declare-campaign-usn');
        text.split("\\n").forEach(function(valLine) {
            const vals = valLine.split("\t")
            const org = vals[0] ?? null
            const countNp = vals[1] ?? null
            const countNpProvidesReliabeDeclare = vals[2] ?? null
            const countNpProvidesNotRequired = vals[3] ?? null
            const accruedSum = vals[4] ?? null
            
            if (!org) {
                return
            }
            
            // подстановка чисел
            form.find('input[name="DeclareCampaignUsn[' + org + '][count_np]"]').val(clearText(countNp))            
            form.find('input[name="DeclareCampaignUsn[' + org + '][count_np_provides_reliabe_declare]"]').val(clearText(countNpProvidesReliabeDeclare))
            form.find('input[name="DeclareCampaignUsn[' + org + '][count_np_provides_not_required]"]').val(clearText(countNpProvidesNotRequired))
            form.find('input[name="DeclareCampaignUsn[' + org + '][accrued_sum]"]').val(clearText(accruedSum))
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
            return text.replace(/\s/g, '')
        }
        return text
    }

JS); ?>

    <?php Pjax::end() ?>

</div>






