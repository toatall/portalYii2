<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    
    'pageSize' => 10,
        
    // user
    'user' => [
        // поиск в ActiveDirectory информации о пользователе по его логину
        'findInAD' => true,
        // использовать ntlm аутентификацию
        'useLdapAuthenticated' => true,
    ],

    // конференции (вкс, собрания)
    'conference' => [
        'notifyMailAddress' => '8600_notifyVksUfns@regions.tax.nalog.ru',
        // настройка доступа
        'access' => [
            'vks-ufns' => [
                //'users' => [],
                'groups' => ['@'],
                //'groups-ad' => [],
            ],
            // для ВКС с ФНС
            'vks-fns' => [
                //'users' => [],
                //'groups' => [],
                'groups-ad' => ['u8600-Informatizacii', 'u8600-Rukovodstvo', 'u8600-Obshhij'],                
            ],
            // для ВКС с внешними организациями
            'vks-external' => [
                //'users' => [],
                //'groups' => [],
                'groups-ad' => ['u8600-Informatizacii', 'u8600-Rukovodstvo', 'u8600-Obshhij'],
            ],
            // для собраний
            'conference' => [
                //'users' => [],
                //'groups' => [],
                'groups-ad' => ['u8600-Informatizacii', 'u8600-Rukovodstvo', 'u8600-Obshhij'],
            ],
        ],
    ],

    // отделы
    'department' => [
        // сотрудники отдела
        'card' => [
            // путь
            'pathImage' => '/files/{code_no}/department_card_image/',
            // максимальная ширина изображения
            'maxHeightPhoto' => 1000,
        ],
        // отраслевые проекты
        'OP' => [
            'index' => 12,
            'path' => '/files/8600/department/op/{id}/',
            'editors' => [
                '8600-90-331',
                //'u8600-AiPNP',
                '8600-90-303',
                '8600-90-620',
                '8600-90-593',
                '8600-90-477',
                '8600-90-289',
                '8600-90-546',
                '8600-90-311',
            ],
        ],
    ],

    // новости / страницы
    'news' => [
        'thumbnailPrefix' => 'thumb',
        'defaultThumb' => '/img/no_image_available.jpeg',
        // каталоги
        'path' => [
            'root' => '/files/{code_no}/{module}/{id}',
            'files' => '/files/{code_no}/{module}/{id}/documents/',
            'images' => '/files/{code_no}/{module}/{id}/image_gallery/',
            'thumbnail' => '/files/{code_no}/{module}/{id}/miniature_image/',
        ],
        // размеры изображений
        'size' => [
            'imageMaxHeight' => 1080,
            'imageMaxWidth' => 1920,
            'thumbnailMaxHeight' => 500,
            'thumbnailMaxWidth' => 0,
        ],
    ],

    // структура
    'tree' => [
        // модуль по-умолчанию (для пользвателей, без роли администратора)
        'defaultModule' => 'page',
    ],

    // телефоны
    'telephone' => [
        'path' => '/files/telephones/',
    ],

    // анкетирование по ГР
    'regecr' => [
        'colors' => [
            'chart_count_create' => 'rgb(255, 99, 132)', // red
            'chart_count_vote' => 'rgb(255, 159, 64)', // organge
            'chart_avg_eval_a_1_1' => 'rgb(75, 192, 192)', // green
            'chart_avg_eval_a_1_2' => 'rgb(54, 162, 235)', // blue
            'chart_avg_eval_a_1_3' => 'rgb(201, 203, 207)', // gray
        ],
    ],

    // проект "Обращения"
    'zg' => [
        // Шаблоны ответов на однотипные обращения
        'template' => [
            'pageSize' => 20,
            // группы и учетные записи доступа
            'editAccounts' => [
                'u8600-Obshhij',
                '8600-90-241',
                '8600-90-446',
                '8600-90-331',
            ],
        ],
    ],

    // тестирование
    'test' => [
        // доступ
        'access' => [
            'viewStatistic' => [
                '8600-90-507',
                '8600-90-374',
                '8600-90-572', // костерина
            ],
            'manage' => [

            ],
        ],
    ],

    // достка почета
    'hallFame' => [
        'path' => '/repository/board_fame/',
        'intervalChangeImages' => 3, // seconds
        'extensionImages' => ['JPG','JPEG','BMP','PNG','GIF'],
    ],

    // Наставничество
    'mentor' => [
        'path' => [
            'files' => '/files/mentor/{id}',
        ],
    ],
    
    // модули
    'modules' => [
        // конкурсы
        'events' => [
            // конкурс навстречу искусству
            'contest-atrs' => [
                'images' => '/files/8600/events/contestAtrs/',
            ],
                        
        ],
    ],
    
];
