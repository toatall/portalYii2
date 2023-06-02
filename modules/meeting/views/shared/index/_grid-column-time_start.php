<?php
/** @var \yii\web\View $this */

use app\modules\meeting\models\ar\ARMeeting;
use yii\bootstrap5\Html;

return [
    'attribute' => 'time_start', 
    'headerOptions' => [
        'style' => 'width: 7rem;'
    ],               
    'format' => 'raw',
    'value' => function(ARMeeting $model) {
        return 
            Html::tag('div',
                '<i class="far fa-clock-four"></i> '. $model->time_start, 
                [
                    'class' => 'calendar-time border text-center p-2 rounded',
                    'data-bs-toggle' => 'tooltip',
                    'data-bs-trigger' => 'hover',
                    'data-bs-title' => 'Время окончания: ' . ($model->time_end ? $model->time_end : 'неизвестно'),
                ]);                                       
    }
];
