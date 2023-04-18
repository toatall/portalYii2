<?php
    /** @var yii\web\View $this */

use yii\bootstrap5\Accordion;

?>

<?= Accordion::widget([
    'id' => 'accordion_zayavki',
    'items' => [ 
        [
            'label' => 'Перечень информационных, программных и аппаратных ресурсов УФНС России по Ханты-Мансийскому автономному округу - Югре',
            'content' => <<<HTML
                <div>
                    <p><a href="/files_static/8600/ib/zayavki/02_Перечень к приказу.xlsx" target="_blank">Перечень информационных, программных и аппаратных ресурсов УФНС России по Ханты-Мансийскому автономному округу - Югре</a></p>
                </div>
            HTML,
        ],
        [
            'label' => 'Формы заявок',
            'content' => <<<HTML
                <div>
                    <p><a href="/files_static/8600/ib/zayavki/Заявка на доступ АИС Налог-3.docx" target="_blank">Заявка на доступ АИС Налог-3</a></p>
                    <p><a href="/files_static/8600/ib/zayavki/Заявка на доступ АИС Налог-3 (с контекстом иного НО).docx" target="_blank">Заявка на доступ АИС Налог-3 (с контекстом иного НО)</a></p>
                    <p><a href="/files_static/8600/ib/zayavki/Заявка на доступ к ИР Управления.docx" target="_blank">Заявка на доступ к ИР Управления</a></p>
                    <p><a href="/files_static/8600/ib/zayavki/Заявка на доступ к ИР Управления (для Инспекций).docx" target="_blank">Заявка на доступ к ИР Управления (для Инспекций)</a></p>
                    <p><a href="/files_static/8600/ib/zayavki/Заявка на доступ к ФИР.docx" target="_blank">Заявка на доступ к ФИР</a></p>
                    <p><a href="/files_static/8600/ib/zayavki/Заявка на выдачу электронного носителя информации.doc" target="_blank">Заявка на выдачу  электронного носителя информации</a></p>                 
                    <p><a href="/files_static/8600/ib/zayavki/Реестр шаблонов с ограничениями доступа.xlsx?1" target="_blank">Реестр шаблонов АИС «Налог-3», доступ к которым ограничен письмами ФНС России</a></p>
                </div>
            HTML,
        ],
        [
            'label' => 'Инструкция по направлению заявки в электронном виде',
            'content' => <<<HTML
                <div>                    
                    <p>Служебная записка отдела информационных технологий <strong>от 11.10.2022 № 22-09/09925-СЗ@</strong></p>
                    <p><a href="/files_static/8600/ib/zayavki/Инструкция по направлению заявок на доступ к информационным ресурсам в электронном виде.pdf" target="_blank">Инструкция по направлению заявок на доступ к информационным ресурсам в электронном виде</a></p>
                </div>
            HTML,
        ],
        // [
        //     'label' => 'Реестр шаблонов АИС «Налог-3», доступ к которым ограничен письмами ФНС России',
        //     'content' => <<<HTML
        //         <div>                         
        //             <p class="mt-3">
        //                 <a href="/files_static/8600/ib/zayavki/Реестр шаблонов с ограничениями доступа.xlsx" 
        //                 target="_blank">Реестр шаблонов АИС «Налог-3», доступ к которым ограничен письмами ФНС России</a>
        //             </p>
        //         </div>
        //     HTML,
        // ],
        [
            'label' => 'Инструкции по определению необходимых для работы шаблонов и ролей',
            'content' => <<<HTML
                <div>     
                    <p class="mt-3">
                        <a href="/files_static/8600/ib/zayavki/Как определить название необходимого шаблона_роли 1.pdf" 
                            target="_blank">Как определить название необходимого шаблона роли 1</a><br />
                        <a href="/files_static/8600/ib/zayavki/Как определить название необходимого шаблона_роли 2.pdf" 
                            target="_blank">Как определить название необходимого шаблона роли 2</a><br />
                    </p>
                </div>
            HTML,
        ],
    ]

]) ?>