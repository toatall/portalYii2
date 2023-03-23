<?php

/** @var \yii\web\View $this */
/** @var string $content */

use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Breadcrumbs;
use app\modules\admin\assets\AppAsset;
use app\assets\ModalViewerAssetBs5;

AppAsset::register($this);
ModalViewerAssetBs5::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php
        NavBar::begin([
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-expand-md navbar-light fixed-top bg-light border-bottom',
            ],            
            'innerContainerOptions' => [
                'class' => 'container-fluid px-5',
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav text-dark me-auto'],
            'encodeLabels' => false,
            'items' => [
                ['label' => '<i class="fas fa-home"></i> Главная', 'url' => ['/admin/default/index'], 'visible' => (!Yii::$app->user->isGuest)],
                ['label' => '<i class="fas fa-external-link-alt"></i> Портал', 'url' => ['/site/index']],
                ['label' => '<i class="fas fa-user-cog"></i> Администрирование', 'items' => [
                    ['label' => 'Пользователи', 'url' => ['/admin/user/index']],
                    ['label' => 'Группы', 'url' => ['/admin/group/index']],
                    ['label' => 'Роли', 'url' => ['/admin/role/index']],
                    ['label' => 'Модули', 'url' => ['/admin/module/index']],
                    ['label' => 'Голосование', 'url' => ['/admin/vote/index']],
                    '<div class="divider"></div>',
                    ['label' => 'Организации', 'url' => ['/admin/organization/index']],
                    ['label' => 'Меню', 'url' => ['/admin/menu/index']],
                ], 'visible' => (Yii::$app->user->can('admin'))],                                
                ['label' => '<i class="fas fa-columns"></i> Контент', 'items' => [
                    ['label' => 'Структура', 'url' => ['/admin/tree/index']],
                    ['label' => 'Отделы', 'url' => ['/admin/department/index']],
                ], 'visible' => (!Yii::$app->user->isGuest)],
                Yii::$app->user->isGuest ? ['label' => 'Вход', 'url' => ['/site/login']] : 
                    ('<li class="nav-item">'
                        . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
                        . Html::submitButton(
                            '<i class="fas fa-sign-out-alt"></i> Выход (' . Yii::$app->user->identity->username . ')',
                            ['class' => 'nav-link btn btn-link logout text-dark']
                        )
                        . Html::endForm()
                    . '</li>'),

            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav text-dark'],
            'items' => [
                Yii::$app->user->isGuest ? ('') : 
                    ([
                        'label' => '<i class="fas fa-building"></i> (' . \Yii::$app->user->identity->current_organization . ')', 
                        'encode' => false, 
                        'url' => ['/admin/organization/list', 'backUrl'=>Yii::$app->getRequest()->getUrl()], 
                        'linkOptions' => ['class' => 'mv-link mv-no-change-url']]
                    )],
        ]);
        NavBar::end();
        ?>

        <div class="container">
            <div class="pt-5 mt-5">
                <?= Breadcrumbs::widget([
                    'homeLink' => ['label' => 'Главная', 'url' => ['/admin/default/index']],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    'options' => ['class' => 'p-3 lead border rounded bg-light'],
                ]) ?>
                <?= $content ?>
            </div>
        </div>
    </div>

    <div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
        <div id="toast-main" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">            
            <div class="toast-header">
                <strong class="mr-auto">Bootstrap</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                Hello, world! This is a toast message.
            </div>
        </div>
    </div>

    <div class="position-fixed bottom-0 right-0" style="z-index: 5; right: 0; bottom: 0;">
        <div id="toast-alert-danger" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">                        
            <div class="toast-header">
                <strong class="mr-auto text-danger toast-title">Bootstrap</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body bg-danger text-white">
                Hello, world! This is a toast message.
            </div>
        </div>
    </div>



    <footer class="footer">
        <div class="container">
            <p class="text-center">
                &copy; Административная часть Портала УФНС по Ханты-Мансийскому автономному округу - Югре <?= date('Y') ?>
            </p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>