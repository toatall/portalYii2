<?php

return [
    
    // emails
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    
    // количество записей (по умолчанию)
    'pageSize' => 10,

    // настройка для виджетов от karik-v\
    'bsVersion' => '5.x',
        
    // user
    'user' => [
        // поиск в ActiveDirectory информации о пользователе по его логину
        // 'findInAD' => true,
        // использовать ntlm аутентификацию
        // 'useLdapAuthenticated' => true,
        'useWindowsAuthenticate' => true,
               
        // настройка профилей
        'profile' => [
            // каталог загрузки изображений пользователей (аватаров)
            'upload' => '/public/upload/portal/user/profile/',
            // изображение по умолчанию (аватар)
            'defaultPhoto' => '/public/upload/portal/user/profile/user-default.png',
        ],
    ],

    // конференции (вкс, собрания)
    // 'conference' => [
    //     // уведомление о ВКС с УФНС
    //     'notifyMailAddress' => '8600_notifyVksUfns@nalog.ru',
    //     // уведомление о новых заявках
    //     'notifyMailAddressAppeal' => '8600_notifyConferenceAppeal@nalog.ru',
    //     // настройка доступа
    //     'access' => [
    //         'vks-ufns' => [
    //             //'users' => [],
    //             'groups' => ['@'],
    //             //'groups-ad' => [],
    //         ],
    //         // для ВКС с ФНС
    //         'vks-fns' => [
    //             //'users' => [],
    //             //'groups' => [],
    //             'groups-ad' => ['u8600-Informatizacii', 'u8600-Rukovodstvo', 'u8600-Obshhij'],                
    //         ],
    //         // для ВКС с внешними организациями
    //         'vks-external' => [
    //             //'users' => [],
    //             //'groups' => [],
    //             'groups-ad' => ['u8600-Informatizacii', 'u8600-Rukovodstvo', 'u8600-Obshhij'],
    //         ],
    //         // для собраний
    //         'conference' => [
    //             //'users' => [],
    //             //'groups' => [],
    //             'groups-ad' => ['u8600-Informatizacii', 'u8600-Rukovodstvo', 'u8600-Obshhij'],
    //         ],
    //     ],        
    //     // видеоконференции по сервису Контур.Толк
    //     'kontur.talk' => [
    //         'roles' => [
    //             'moderator' => 'VKS.KonturTalk.moderator',
    //         ],
    //     ],
    // ],

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
            'videos' => '/files/{code_no}/{module}/{id}/video/'
        ],
        // размеры изображений
        'size' => [
            'imageMaxHeight' => 1080,
            'imageMaxWidth' => 1920,
            'thumbnailMaxHeight' => 500,
            'thumbnailMaxWidth' => 300,
        ],
    ],

    // структура
    'tree' => [
        // модуль по-умолчанию (для пользвателей, без роли администратора)
        'defaultModule' => 'page',
    ],

    'organization' => [
        'path' => [
            'uploadImages' => '/public/upload/portal/organization/images/{code}/',
        ],
    ],
 
    // телефоны
    'telephone' => [
        'path' => '/files/telephones/',
        'SOAPServiceUrl' => 'http://86000-app012:8055/WSDLServices.nsf/telephones?WSDL',
        'SOAPUser' => 'WebUser',
        'SOAPPassword' => '123456789',
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
        'email-goverment' => [
            'roles' => [
                'moderator' => 'zg.email-goverment.moderator',
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

    // Декларационная компания по УСН
    'declare-campaign-usn' => [        
        'role-moderator' => 'moderator.declare-campaign-usn',
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
        // кадровые проекты
        'kadry' => [
            'award' => [
                'roles' => [
                    'moderator' => 'kadry.award.moderator',
                    'reader' => 'kadry.award.reader',
                ],
            ],
            // учебные материалы
            'education' => [
                // файл с описанием учебных материалов
                'jsonFile' => '/public/content/kadry/education/data.json',
                'uploadPath' => '/public/upload/kadry/education/',
                'thumbnailNotSet' => '/public/content/kadry/education/no-image-course.jpg',
            ],
            // раздел лучший профессионал 
            'best-professional' => [
                'path' => [
                    'images' => '/public/upload/kadry/best-professional/images/{id}/',
                ],
                'roles' => [
                    'moderator' => 'kadry-best-professional-moderator',
                ],
            ],
        ],

        // книжная полка
        'bookshelf' => [
            'uploadPathBook' => '/public/upload/bookshelf/images/book/',
            'defaultImageBook' => '/public/content/bookshelf/images/book.png',
            'uploadPathWriter' => '/public/upload/bookshelf/images/writer/',
            'uploadWhatReadingPhoto' => '/public/upload/bookshelf/images/what-reading/{id}/',
            'defaultImageWriter' => '/public/content/bookshelf/images/writer-1600x800.png',
            // роли
            'roles' => [
                // default role: bookshelf.admin
                //'books-admin' => 'role-name',
            ],
        ],

        // исполнение задач
        'executeTasks' => [
            'roles' => [
                'moderator' => 'execute-tasks.moderator',
            ],
            'department' => [
                'path' => '/public/upload/execute-tasks/department/{id}/',
            ],
            'organization' => [
                'path' => '/public/upload/execute-tasks/organization/{id}/',
            ],
        ],

        // информационный ресурс по предоставлению информации ограниченного доступа
        'restricteddocs' => [
            'uploadPath' => '/public/upload/restricteddocs/{id}/',
            'roles' => [
                'moderator' => 'restricteddocs.moderator',
            ],
        ],

        // конкурс новобранцев
        'rookie' => [
            // конкурс tiktok
            'tiktok' => [
                'videos' => '/public/upload/rookie/tiktok/videos/',
            ],
        ],

        'contest' => [
            'space' => [
                'uploadPath' => '/public/upload/contest/space/{id}/',
            ],
        ],
        
    ],    

    'lefehack' => [
        'path' => [
            'files' => '/public/upload/portal/lifehack/{id}/'
        ],
    ],

    'calendar' => [
        'birhdays' => [
            'type_text' => 'Дни рождения',
            'color_day' => 'calendar-badge-birthday',
            'color_text' => 'danger',
        ],
    ],

    'protocol' => [
        'path' => [
            'files_main' => '/public/upload/portal/protocol/{id}/main/',
            'files_execute' => '/public/upload/portal/protocol/{id}/execute/',
        ],
        'roles' => [
            'moderator'=> 'protocol-moderator',
        ],
    ],

    // раздел изменения в законодателстве
    'change-legislation' => [
        'roles' => [
            'moderator' => 'change-legislation-moderator',
        ],
    ],

    // раздел "Анкетирование мигрантов"
    'migrants-questionnation' => [
        'roles' => [
            'moderator' => 'migrants-questionnation-moderator',
        ],
    ],

  
    
];
