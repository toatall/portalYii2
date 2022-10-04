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
    
    <?php $form->errorSummary($models) ?>

    <table class="table table-bordered">
        <tr>
            <th>Код НО</th>
            <th>Начисления</th>
            <th>Поступления с 01.09.2021</th>
            <th>Sum3</th>
            <th>СМС (средний)</th>
            <th>СМС (НИФЛ)</th>
            <th>СМС (ТП)</th>
            <th>СМС (ЗН)</th>
            <th>Оставшаяся сумма до 80 %(всего)</th>
            <th>Оставшаяся сумма до 80 % (НИФЛ)</th>
            <th>Оставшаяся сумма до 80 % (ТН)</th>
            <th>Оставшаяся сумма до 80 % (ЗН)</th>
            <th>Прирост СМС показателя с предыдущей даты</th>   
            <th>Достижение КПЭ (95 %)</th>              
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
            <td><?= $form->field($model, "[$org]sum3")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$org]sms")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$org]sms_1")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$org]sms_2")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$org]sms_3")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$org]sum_left_all")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$org]sum_left_nifl")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$org]sum_left_tn")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$org]sum_left_zn")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$org]growth_sms")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$org]kpe_persent")->textInput()->label(false) ?></td>
        </tr>
    <?php endforeach; ?>
    </table>
   
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
   
    <?php $form->end() ?>
   
    <?php Pjax::end() ?>
</div>
