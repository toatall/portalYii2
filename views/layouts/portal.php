<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\widgets\Alert;
use app\widgets\NavBarLeft;
use app\models\menu\MenuBuilder;
use app\assets\ModalViewerAsset;

AppAsset::register($this);
ModalViewerAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.png" />
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
<?php
/*
$this->registerJs(<<<JS

    $('[data-toggle="popover"]')
        .popover({trigger: 'hover'})
        .popover();

    $('[data-toggle="tooltip"]').popover();

    $('body')
        .popover({ selector: '[data-toggle="popover"]' })
        .tooltip({ selector: '[data-toggle="tooltip"]' });
    
JS
);*/
?>
</head>
<body>
<?php $this->beginBody() ?>
<div id="div-loader" class="loader loader-default" data-halfs></div>
<?php
    // подключаем 30-летие
    //echo $this->render('top_thirty');
?>
<div class="wrap black-wall">
    <?= $this->render('top') ?>
<!--    <div id="logo-background">-->
<!--        <div id="logo-image"></div>-->
<!--    </div>-->
    <?php
    
    NavBar::begin([
        'id' => 'navbar-main-menu',
        'brandLabel' => false,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inner',           
        ],
        'innerContainerOptions' => [
            'style' => 'width: 100%;',
        ],
    ]);
    
    $menuItems = MenuBuilder::buildMain();
    
    $menuItemsRight = [];
    if (Yii::$app->user->isGuest) {       
        $menuItemsRight[] = ['label' => 'Вход', 'url' => ['/site/login']];
    } else {
        $menuItemsRight[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Выход (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout', 'style' => 'padding:0; padding-top:5px;']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'items' => $menuItems,
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItemsRight,
    ]);
    
    NavBar::end();
    ?>

    <div class="container-fluid">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <div class="col-sm-2 col-md-2" style="padding-left:0;">
            
            <?= NavBarLeft::widget([
                'encodeLabels' => false,
                'options' => [
                    'class' => 'dropdown-menu dropdown-menu-main dropdown-menu-wrap',                    
                ],
                'dropDownCaret' => '',
                'items' => MenuBuilder::buildLeft(),
            ]); ?>

            <?php
                MenuBuilder::initLeftMenuAdd();
                foreach (MenuBuilder::buildLeftAdd() as $menuItem) {
                    echo NavBarLeft::widget([
                        'encodeLabels' => false,
                        'options' => [
                            'class' => 'dropdown-menu dropdown-menu-main dropdown-menu-wrap',
                        ],
                        'dropDownCaret' => '',
                        'items' => $menuItem,
                    ]);
                }
            ?>
                                    
        </div>
        <div class="col-sm-10 col-md-10">
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>
</div>

<footer class="footer" style="margin-top: 20px; height: auto;">
    <div class="container-fluid" style="padding: 0 100px;">
        <div class="row">
            <div class="col-sm-4">
                <h5><strong>Внутренние сайты и сервисы ФНС России</strong></h5>
                <ul class="list-unstyled">
                    <li><a href="http://portal.tax.nalog.ru" target="_blank">Портал ФНС России</a></li>
                    <li><a href="http://support.tax.nalog.ru" target="_blank">Портал ФКУ "Налог-Сервис" ФНС России</a></li>
                    <li><a href="https://support.gnivc.ru" target="_blank">Сайт технической поддержки АО "ГНИВЦ"</a></li>
                    <li><a href="http://edu.tax.nalog.ru" target="_blank">Образовательный портал ФНС России</a></li>
                    <li><a href="http://support.tax.nalog.ru/esk/phone/" target="_blank">Телефонный справочник работников ФНС / ФКУ</a></li>
                    <li><a href="http://wiki.tax.nalog.ru/mw/index.php" target="_blank">Глоссарий ФНС России</a></li>
                    <li><a href="http://riski.regions.tax.nalog.ru/autorize.html" target="_blank">Реестр рисков</a></li>
                    <li><a href="http://lk3-usr.tax.nalog.ru/user/auth/index" target="_blank">Кабинет налогоплательщика юридического лица</a></li>
                    <li><a href="https://rdmz-nlb-nginx.lkfl21.tax.nalog.ru/lkfl-ofc/login" target="_blank">Личный кабинет налогоплательщика — физического лица</a></li>                    
                    <li><a href="http://consultant.tax.nalog.ru" target="_blank">Консультант Плюс</a></li>
                </ul>                        
            </div>
            <div class="col-sm-4">
                <h5><strong>Аналитическая подсистема АИС "Налог-3"</strong></h5>
                <ul class="list-unstyled">
                    <li><a href="http://ias.ais3.tax.nalog.ru/ais/" target="_blank">Программный комплекс информационно-аналитической работы</a></li>
                    <li><a href="https://n7701-koe606.dpc.tax.nalog.ru/" target="_blank">Система управления запросами к озеру данных "Экспедитор"</a></li>
                    <li><a href="http://10.253.200.25:9082/#/rus/" target="_blank">Автоматизированная система контроля кассовой техники (АСК ККТ)</a></li>
                    <li><a href="http://marmnpd.tax.nalog.ru:8081" target="_blank">Автоматизированное рабочее место «Налог на  профессиональный доход» (МАРМ НПД)</a></li>
                    <li><a href="https://bo.dpc.tax.nalog.ru" target="_blank">Государственный информационный ресурс бухгалтерской (финансовой) отчетности (ГИР БО)</a></li>                   
                    <li><a href="http://ias.ais3.tax.nalog.ru/uprrep" target="_blank">Информационно-аналитическая подсистема "Управленческий учет"</a></li>
                </ul>                
            </div>
            <div class="col-sm-4">
                <h5><strong>Внутренние сервисы Управления</strong></h5>
                <ul class="list-unstyled">
                    <li><?= ''//Html::a('Рекомендуемые браузеры', array('site/browsers')); ?></li>
                    <li><a href="http://u8600-app045:81" target="_blank">Реестр невзысканных сумм по налоговым проверкам (ВНП, КНП)</a></li>
                    <li><a href="http://u8600-app045:82" target="_blank">Реестр прав доступа внешних ресурсов</a></li>
                    <li><a href="http://u8600-app045:83" target="_blank">Электронный архив</a></li>
                    <li><a href="http://u8600-app045:85" target="_blank">Реестр проверок органами государственного контроля и надзора</a></li>
                    <li><a href="http://u8600-app045:86" target="_blank">Реестр МРГ</a></li>
                    <li><a href="http://u8600-app036" target="_blank">Бывшие сотрудники</a></li>
                    <li><a href="http://u8600-app012:88" target="_blank">Проект "Обращения"</a></li>
                </ul>
            </div>            
        </div>                
    </div>
    <hr />
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
