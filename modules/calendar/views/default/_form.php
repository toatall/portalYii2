<?php

use app\modules\calendar\models\Calendar;
use kartik\select2\Select2;
use yii\bootstrap5\Html;
use kartik\widgets\ActiveForm;
use yii\web\JsExpression;

/** @var yii\web\View $this */
/** @var Calendar $model */
/** @var yii\widgets\ActiveForm $form */
/** @var string $date */
?>

<div class="calendar-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'data-pjax' => true,
        ],
    ]); ?>

    <?= $form->errorSummary($model) ?>   

    <?= 
    $form->field($model, 'type_text')->widget(Select2::class, [
        'data' => $model->dropDownTypeText(),
    ])
     ?>

<?php 
$result = <<< JS
    function format(data) {
        return '<span class="badge bg-' + data.id + ' rounded" style="font-size: 1em; font-weight: normal; padding: 0.3rem;">' + data.text + '</span>';
    } 
JS; ?>
    <?= $form->field($model, 'color')->widget(Select2::class, [
        'data' => $model->colorsDropdown(),
        'pluginOptions' => [
            'templateResult' => new JsExpression($result),
            'escapeMarkup' => new JsExpression('function(m) { return m; }'),
            'templateSelection' => new JsExpression($result),
        ]
    ]) ?> 
       
    <?= $form->field($model, 'description') ?> 
    
    <?php if (Yii::$app->user->can('admin')): ?>
        <?= $form->field($model, 'is_global')->checkbox([
            'template' => '<div class="custom-control custom-switch">{input} {label}</div><div>{error}</div>',
        ]) ?>
    <?php endif; ?> 

    <div class="border-top">
        <div class="btn-group  pt-1">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Отмена', ['/calendar/default/view', 'date'=>$date], ['class' => 'btn btn-secondary', 'pjax' => 1]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
