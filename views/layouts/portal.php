<?php

/** @var \yii\web\View $this */
/** @var string $content */

use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;

use app\models\menu\MenuBuilder;
use app\assets\ModalViewerAsset;
use app\assets\AppAsset;
use app\widgets\AlertConferenceApprove;
use yii\bootstrap4\Alert;
use yii\widgets\Menu;

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

    <?php
        
    // главное меню
    NavBar::begin([
        'id' => 'navbar-main-menu',
        'brandLabel' => false,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-light bg-light border-bottom py-1',
        ],
        'renderInnerContainer' => false,
        'collapseOptions' => false,        
    ]);
    
    $menuItems = MenuBuilder::buildMain();    
    
    $menuItemsRight = [];
    if (Yii::$app->user->isGuest) {       
        $menuItemsRight[] = ['label' => 'Вход', 'url' => ['/site/login']];
    } else {
        $menuItemsRight[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post', ['class'=>'form-inline'])
            . Html::submitButton(
                '<i class="fas fa-logout"></i> Выход (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav mr-auto text-dark'],
        'items' => $menuItems,
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav text-dark'],
        'items' => $menuItemsRight,
    ]);
    
    NavBar::end();
    
    ?>

    <div class="container-fluid">                       

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'options' => [
                'class' => 'mt-2',
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
                    //MenuBuilder::initLeftMenuAdd();
                    
                    foreach (MenuBuilder::buildLeftAdd() as $menuItem) {
                        echo Menu::widget([
                        'items' => $menuItem,  
                        'encodeLabels' => false,
                        'options' => ['class' => 'dropdown-menu dropdown-menu-main dropdown-menu-wrap'],
                        'submenuTemplate' => "\n<ul class=\"dropdown-menu\">\n{items}\n</ul>\n",
                    ]);

                    

                        /*
                        echo NavBarLeft::widget([
                            'encodeLabels' => false,
                            'options' => [
                                'class' => 'dropdown-menu dropdown-menu-main dropdown-menu-wrap',
                            ],
                            'dropDownCaret' => '',
                            'items' => $menuItem,
                        ]);*/
                    }
                 ?>
                      
            </div>
            <div class="col-10">   
                <?php if (Yii::$app->user->can('permConferenceApprove')): ?>
                    <?= AlertConferenceApprove::widget() ?>
                <?php endif; ?>

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
                    <li><a href="http://riski.regions.tax.nalog.ru/autorize.html" target="_blank">Реестр рисков</a></li>
                    <li><a href="http://lk3-usr.tax.nalog.ru/user/auth/index" target="_blank">Кабинет налогоплательщика юридического лица</a></li>
                    <li><a href="https://rdmz-nlb-nginx.lkfl21.tax.nalog.ru/lkfl-ofc/login" target="_blank">Личный кабинет налогоплательщика — физического лица</a></li>                    
                    <li><a href="http://consultant.tax.nalog.ru" target="_blank">Консультант Плюс</a></li>
                </ul>                        
            </div>
            <div class="col-4">
                <h5><strong>Аналитическая подсистема АИС "Налог-3"</strong></h5>
                <ul class="list-unstyled">
                    <li><a href="http://ias.ais3.tax.nalog.ru/ais/" target="_blank">Программный комплекс информационно-аналитической работы</a></li>
                    <li><a href="https://n7701-koe606.dpc.tax.nalog.ru/" target="_blank">Система управления запросами к озеру данных "Экспедитор"</a></li>
                    <li><a href="http://marmnpd.tax.nalog.ru:8081" target="_blank">Автоматизированное рабочее место «Налог на  профессиональный доход» (МАРМ НПД)</a></li>
                    <li><a href="https://bo.dpc.tax.nalog.ru" target="_blank">Государственный информационный ресурс бухгалтерской (финансовой) отчетности (ГИР БО)</a></li>                   
                    <li><a href="http://ias.ais3.tax.nalog.ru/uprrep" target="_blank">Информационно-аналитическая подсистема "Управленческий учет"</a></li>
                </ul>                
            </div>
            <div class="col-4">
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
    <div class="pb-2 text-center">
        <p>&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
