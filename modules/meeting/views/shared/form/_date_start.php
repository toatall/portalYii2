<?php
/** @var \yii\web\View $this */
/** @var \app\modules\meeting\models\Meeting $model */
/** @var \yii\bootstrap5\ActiveForm $form */

use kartik\datetime\DateTimePicker;
?>

<?= $form->field($model, 'date_start_str')->widget(DateTimePicker::class, [
    'pluginOptions' => [
        'todayHighlight' => true,
        'todayBtn' => true,
        'autoclose' => true,
        'format' => 'dd.mm.yyyy hh:ii',
    ],
    'options' => ['autocomplete' => 'off'],
]) ?>