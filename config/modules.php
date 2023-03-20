<?php

return  [
    // админка
    'admin' => [
        'class' => 'app\modules\admin\Module',
    ],
    // проекты новобранцев
    'rookie' => [
        'class' => 'app\modules\rookie\Module',            
    ],
    // тестирование
    'test' => [
        'class' => 'app\modules\test\Module',            
    ],        
    'gridview' => [
        'class' => '\kartik\grid\Module',
    ],       
    /** @todo надо разобрать */ 
    'events' => [
        'class' => 'app\modules\events\Module',
    ],
    // раздел только для администратора
    'manager' => [
        'class' => 'app\modules\manager\Module',
    ],
    // конкурсы
    'contest' => [
        'class' => 'app\modules\contest\Module',
    ],
    // раздел для отдела кадров
    'kadry' => [
        'class' => 'app\modules\kadry\Module',
    ],
    // календарь
    'calendar' => [
        'class' => 'app\modules\calendar\Module',
    ],
    'spa' => [
        'class' => 'app\modules\spa\Module',
    ],
    'bookshelf' => [
        'class' => 'app\modules\bookshelf\Module',
    ],
    // опрос
    'quiz' => [
        'class' => 'app\modules\quiz\Module',
    ],
    // исполнение задач
    'executetasks' => [
        'class' => 'app\modules\executetasks\Module',
    ],
    // информационный ресурс по предоставлению информации ограниченного доступа
    'restricteddocs' => [
        'class' => 'app\modules\restricteddocs\Module',
    ],

    // кампания по уплате имущественных налогов
    'paytaxes' => [
        'class' => 'app\modules\paytaxes\Module',
    ],   
    
    'comment' => 'app\modules\comment\Module',
];