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
use app\assets\FontAwesomeAsset;
use app\models\Footer;
use app\modules\test\assets\TestAsset;
use yii\widgets\Menu;

AppAsset::register($this);
ModalViewerAssetBs5::register($this);
TestAsset::register($this);
FontAwesomeAsset::register($this);
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

                    <?php if (isset($this->blocks['addon-menu'])): ?>
                        <?= ''//$this->blocks['addon-menu'] ?>
                    <?php endif; ?>

                    <?php if (isset($this->params['addon-menu'])): ?>
                        <?= $this->params['addon-menu'] ?>
                    <?php endif; ?>


                    
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
    

    <?php
        $cache = Yii::$app->cache;
        echo $cache->getOrSet(Footer::getCahceName(), function() {
            return $this->render('parts/footer');
        }, 0);        
     ?>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>