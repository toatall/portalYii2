<?php
/** @var \yii\web\View $this */
/** @var \app\modules\meeting\models\Meeting $model */
/** @var \yii\bootstrap5\ActiveForm $form */

use kartik\time\TimePicker;
?>

<?= $form->field($model, 'duration_str')->widget(TimePicker::class, [
    'pluginOptions' => [
        'showMeridian' => false,
        'defaultTime' => '01:00',
    ],
]) ?>