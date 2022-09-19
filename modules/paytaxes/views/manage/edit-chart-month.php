<?php

/** @var \yii\web\View $this */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\widgets\Pjax;

/** @var array $data */
/** @var app\modules\paytaxes\models\PayTaxesChartMonth[] $models */

?>

<div class="table-responsive">

    <?php Pjax::begin(['id' => 'pjax-pay-taxes-manage-chart-month', 'timeout' => false, 'enablePushState' => false]) ?>
    
    <?php $form = ActiveForm::begin([
        'id' => 'form-pay-taxes-manage-chart-month',
        'options' => [
            'data-pjax' => true,
        ],
    ]) ?>
    
    <?php $form->errorSummary($models) ?>

    <table class="table table-bordered">
        <tr>
            <th>Код НО</th>
            <th>Сумма</th>            
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
        </tr>
    <?php endforeach; ?>
    </table>
   
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
   
    <?php $form->end() ?>
   
    <?php Pjax::end() ?>
</div>
