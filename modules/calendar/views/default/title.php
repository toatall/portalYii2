<?php
/** @var \yii\web\View $this */

use app\modules\calendar\models\Calendar;
use kartik\editable\Editable;
use yii\web\JsExpression;

/** @var \app\modules\calendar\models\CalendarColor $model */
/** @var string $date */
?>

<?php 
$htmlBefore = <<<HTML
<div class="card-header">
    <div class="row">
        <div class="col">
            <small>{header}</small>
        </div>
        <div>{close}</div>
    </div>
</div>
<div class="card-body">
HTML;

$htmlAfter = <<<HTML
</div>
<div class="card-footer panel-footer">
    {loading}{buttons}
</div>
HTML;

$result = <<< JS
    function format(data) {
        return '<span class="badge-' + data.id + ' rounded" style="font-size: 1em; font-weight: normal; padding: 0.3rem;">' + data.text + '</span>';
    } 
JS;
?>

<?= Editable::widget([        
    'model' => $model,        
    'attribute' => 'color',
    'asPopover' => false,
    'inputType' => Editable::INPUT_SELECT2,        
    'size' => 'md',
    'options' => [
        'class' => 'form-control',            
        'data' => ['' => 'не задан'] + Calendar::colorsDropdown(),     
        'pluginOptions' => [
            'templateResult' => new JsExpression($result),
            'escapeMarkup' => new JsExpression('function(m) { return m; }'),
            'templateSelection' => new JsExpression($result),
            'width' => '20rem',
        ],  
        'options' => [
            'style' => 'width:100%',
            'class' => 'col-12',
        ],    
    ],     
    'data' => ['' => 'не задан'] + Calendar::colorsDropdown(),       
    'displayValue' => $model->getDisplayDateWithColor(),
    'editableValueOptions' => [
        'class' => 'kv-editable-link fa-3x',        
    ],
    'pluginEvents' => [
        'editableSuccess' => new JsExpression('function() { updateCaledarAis3(); }'),
    ],
]) ?>