<?php

/** @var \yii\web\View $this */
/** @var string $content */

use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Breadcrumbs;

use app\models\menu\MenuBuilder;
use app\assets\ModalViewerAssetBs5;
use app\assets\AppAsset;
use app\modules\test\assets\TestAsset;
use yii\widgets\Menu;

AppAsset::register($this);
ModalViewerAssetBs5::register($this);
TestAsset::register($this);

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
</head>

<body>
    <?php $this->beginBody() ?>
    <div id="div-loader" class="loader loader-default" data-halfs></div>

    <div class="wrap black-wall">
        <?= $this->render('top') ?>

        <?php

        // главное меню
        NavBar::begin([
            'id' => 'navbar-main-menu',
            'brandLabel' => false,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-expand border-bottom',
            ],            
            'innerContainerOptions' => [
                'class' => 'container-fluid px-3',
            ],
        ]);

        $menuItems = MenuBuilder::buildMain();

        $menuItemsRight = [];
        if (Yii::$app->user->isGuest) {
            $menuItemsRight[] = ['label' => '<i class="fas fa-sign-in-alt"></i> Вход', 'url' => ['/site/login']];
        } else {
            $menuItemsRight = [
                [
                    'label' => '<i class="far fa-user"></i> ' . Yii::$app->user->identity->fio,

                    'items' => [
                        ['label' => '<i class="far fa-user-circle"></i> Профиль', 'url' => ['/user/profile']],
                        ['label' => '<i class="fas fa-sign-out-alt"></i> Выход', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
                    ],
                    'dropdownOptions' => ['class' => 'dropdown-menu dropdown-menu-right'],
                ],
            ];
        }

        echo Nav::widget([           
            'options' => ['class' => 'navbar-nav text-dark me-auto'],
            'items' => $menuItems,
        ]);

        echo Nav::widget([
            'encodeLabels' => false,
            'items' => $menuItemsRight,
        ]);

        NavBar::end();

        ?>

        <div class="container-fluid">

            <!--[if IE]>
            <div class="alert alert-danger mx-4 my-4 display-6 text-center">
                Внимание!<br />
                Браузер Internet Explorer не поддерживается!
            </div>
            <![endif]-->

            <?= Breadcrumbs::widget([
                'homeLink' => ['label' => 'Главная', 'url' => '/'],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'options' => [
                    'class' => 'mt-2 py-2 px-4 border rounded bg-light text-decoration-none',
                ],               
            ]) ?>

            <div class="row mt-2">
                <div class="col-2">
                    
                    <?= Menu::widget([
                        'items' => MenuBuilder::buildLeft(['class' => 'dropdown-submenu']),
                        'encodeLabels' => false,
                        'options' => ['class' => 'dropdown-menu dropdown-menu-main dropdown-menu-wrap'],
                        'submenuTemplate' => "\n<ul class=\"dropdown-menu\">\n{items}\n</ul>\n",
                    ]) ?>

                    <?php
                    foreach (MenuBuilder::buildLeftAdd() as $menuItem) {
                        echo Menu::widget([
                            'items' => $menuItem,
                            'encodeLabels' => false,
                            'options' => ['class' => 'dropdown-menu dropdown-menu-main dropdown-menu-wrap'],
                            'submenuTemplate' => "\n<ul class=\"dropdown-menu\">\n{items}\n</ul>\n",
                        ]);
                    }
                    ?>
                    
                    <ul class="dropdown-menu dropdown-menu-main dropdown-menu-wrap" style="border: none; padding: 0;">
                        <?php foreach (MenuBuilder::buildLeftAddMenuContent() as $menuItem) {
                            echo $menuItem;
                        }
                        ?>
                    </ul>

                </div>
                <div class="col-10">
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>   
    <footer class="footer mt-3 bg-light border-top pt-3" style="height: auto;">
        <div class="container-fluid">
            <div class="row px-5">
                <div class="col-4">
                    <h5><strong>Внутренние сайты и сервисы ФНС России</strong></h5>
                    <ul class="list-unstyled">
                        <li><a href="http://portal.tax.nalog.ru" target="_blank">Портал ФНС России</a></li>
                        <li><a href="http://support.tax.nalog.ru" target="_blank">Портал ФКУ "Налог-Сервис" ФНС России</a></li>
                        <li><a href="https://support.gnivc.ru" target="_blank">Сайт технической поддержки АО "ГНИВЦ"</a></li>
                        <li><a href="http://edu.tax.nalog.ru" target="_blank">Образовательный портал ФНС России</a></li>
                        <li><a href="http://support.tax.nalog.ru/esk/phone/" target="_blank">Телефонный справочник работников ФНС / ФКУ</a></li>
                        <li><a href="http://wiki.tax.nalog.ru/mw/index.php" target="_blank">Глоссарий ФНС России</a></li>
                        <li><a href="http://lk3-usr.tax.nalog.ru/user/auth/index" target="_blank">Кабинет налогоплательщика юридического лица</a></li>
                        <li><a href="https://rdmz-nlb-nginx.lkfl21.tax.nalog.ru/lkfl-ofc/login" target="_blank">Личный кабинет налогоплательщика — физического лица</a></li>
                        <li><a href="http://consultant.tax.nalog.ru" target="_blank">Консультант Плюс</a></li>
                    </ul>
                </div>
                <div class="col-4">
                    <h5><strong>Аналитическая подсистема АИС "Налог-3"</strong></h5>
                    <ul class="list-unstyled">
                        <li><a href="http://ias.ais3.tax.nalog.ru/ais/" target="_blank">Программный комплекс информационно-аналитической работы</a></li>
                        <li><a href="https://wdewebkpe.dpc.tax.nalog.ru/" target="_blank">Система управления запросами к озеру данных "Экспедитор" (промышленный контур)</a></li>
                        <li><a href="https://n7701-koe606.dpc.tax.nalog.ru" target="_blank">Система управления запросами к озеру данных "Экспедитор" (опытный контур)</a></li>
                        <li><a href="http://marmnpd.tax.nalog.ru:8081" target="_blank">Автоматизированное рабочее место «Налог на профессиональный доход» (МАРМ НПД)</a></li>
                        <li><a href="https://bo.dpc.tax.nalog.ru" target="_blank">Государственный информационный ресурс бухгалтерской (финансовой) отчетности (ГИР БО)</a></li>
                        <li><a href="http://ias.ais3.tax.nalog.ru/uprrep" target="_blank">Информационно-аналитическая подсистема "Управленческий учет"</a></li>
                        <li><a href="https://soon.tax.nalog.ru/taps-ofc/executor_dashboard" target="_blank">Омниканальная система (СООН)</a></li>
                        <li><a href="https://iasar.dpc.tax.nalog.ru" target="_blank" data-bs-toggle="popover" data-bs-trigger="hover" title="Письмо ФНС России от 02.03.2023 № 1-4-05/0005@">БД Аренадату</a></li>
                    </ul>
                </div>
                <div class="col-4">
                    <h5><strong>Внутренние сервисы Управления</strong></h5>
                    <ul class="list-unstyled">                    
                        <li><a href="http://86000-portal:82" target="_blank">Реестр прав доступа внешних ресурсов</a></li>
                        <li><a href="http://86000-portal:83" target="_blank">Электронный архив</a></li>
                        <li><a href="http://86000-portal:85" target="_blank">Реестр проверок органами государственного контроля и надзора</a></li>
                        <li><a href="http://86000-portal:86" target="_blank">Реестр МРГ</a></li>
                        <li><a href="http://86000-app036" target="_blank">Бывшие сотрудники</a></li>
                        <li><a href="http://86000-app012:88" target="_blank">Проект "Обращения"</a></li>
                        <li><a href="/kadry/award" target="_blank">Награды и поощрения сотрудников налоговых органов округа</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <hr />
        <div class="pb-2 text-center">
            <p>&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>