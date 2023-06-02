<?php
/** @var \yii\web\View $this */

use app\helpers\DateHelper;
use app\modules\meeting\models\ar\ARMeeting;

return [
    'headerOptions' => [
        'style' => 'width: 7rem;'
    ],
    'label' => 'Дата',
    'attribute' => 'date_start',
    'format' => 'raw',
    'value' => function(ARMeeting $model): string {
        $monthName = DateHelper::getMonthName(date('n', $model->date_start));
        $day = date('d', $model->date_start);
        $year = date('Y', $model->date_start);
        $classToday = (date('dmY') == date('dmY', $model->date_start)) ? ' fw-bold' : '';
        return '
            <div class="calendar rounded shadow-sm text-center" style="width: 6rem;">
                <div class="top rounded-top">
                    ' . $monthName . '
                </div>
                <div class="day border-left border-right' . $classToday . '">' . $day. '</div>
                <div class="bottom rounded-bottom">' . $year . '</div>                            
            </div>';
    },
];
