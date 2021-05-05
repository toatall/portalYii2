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
    <div class="container">
        <p class="pull-left">
            <b>Внутренние сайты и сервисы ФНС России:</b>
            <br /><a href="http://portal.tax.nalog.ru" target="_blank">Портал ФНС России</a>
            <br /><a href="http://support.tax.nalog.ru" target="_blank">Портал ФКУ "Налог-Сервис" ФНС России</a>
            <br /><a href="https://support.gnivc.ru" target="_blank">Сайт технической поддержки АО "ГНИВЦ"</a>
            <br /><a href="http://edu.tax.nalog.ru" target="_blank">Образовательный портал ФНС России</a>
            <br /><a href="http://support.tax.nalog.ru/esk/phone/" target="_blank">Телефонный справочник работников ФНС / ФКУ</a>
            <br /><a href="http://wiki.tax.nalog.ru/mw/index.php" target="_blank">Глоссарий ФНС России</a>
            <br /><a href="http://riski.regions.tax.nalog.ru/autorize.html" target="_blank">Реестр рисков</a>
            <br /><a href="http://lk3-usr.tax.nalog.ru/user/auth/index" target="_blank">Кабинет налогоплательщика юридического лица</a>
            <br /><a href="https://rdmz-nlb-nginx.lkfl21.tax.nalog.ru/lkfl-ofc/login" target="_blank">Личный кабинет налогоплательщика — физического лица</a>
            <br /><a href="http://ias.ais3.tax.nalog.ru/ais/" target="_blank">Программный комплекс информационно-аналитической работы</a>
        </p>
        <p class="pull-right">
            <b>Внутренние сервисы Управления:</b>
            <br /><?= ''//Html::a('Рекомендуемые браузеры', array('site/browsers')); ?>
            <br /><a href="http://u8600-app045:81" target="_blank">Реестр невзысканных сумм по налоговым проверкам (ВНП, КНП)</a>
            <br /><a href="http://u8600-app045:82" target="_blank">Реестр прав доступа внешних ресурсов</a>
            <br /><a href="http://u8600-app045:83" target="_blank">Электронный архив</a>
            <br /><a href="http://u8600-app045:85" target="_blank">Реестр проверок органами государственного контроля и надзора</a>
            <br /><a href="http://u8600-app045:86" target="_blank">Реестр МРГ</a>
            <br /><a href="http://u8600-app036">Бывшие сотрудники</a>
            <br /><a href="http://u8600-app012:88">Проект "Обращения"</a>
        </p>
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
