<?php

/** @var \yii\web\View $this */
/** @var string $content */

use yii\bootstrap4\Alert;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;
use app\modules\admin\assets\AppAsset;
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
            'class' => 'navbar navbar-expand-md navbar-light fixed-top bg-light border-bottom px-4',
        ],
        'renderInnerContainer' => false,
        'collapseOptions' => false,
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav text-dark mr-auto'],
        'items' => [
            ['label' => 'Главная', 'url' => ['/admin/default/index'], 'visible'=>(!Yii::$app->user->isGuest)],
            ['label' => 'Портал', 'url' => ['/site/index']],
            ['label' => 'Администрирование', 'items' => [
                ['label' => 'Пользователи', 'url'=>['/admin/user/index']],
                ['label'=>'Группы', 'url'=>['/admin/group/index']],
                ['label' => 'Роли', 'url'=>['/admin/role/index']],
                ['label'=>'Модули', 'url'=>['/admin/module/index']],
                ['label'=>'Голосование', 'url'=>['/admin/vote/index']],
                //'<li class="divider"></li>',
                ['label' => 'Организации', 'url'=>['/admin/organization/index']],
                ['label'=>'Меню', 'url'=>['/admin/menu/index']],
            ], 'visible'=>(Yii::$app->user->can('admin'))],
            ['label' => 'Контент', 'items' => [
                ['label'=>'Структура', 'url'=>['/admin/tree/index']],
                ['label'=>'Отделы', 'url'=>['/admin/department/index']],
            ], 'visible'=>(!Yii::$app->user->isGuest)],
            ['label'=>'Справка', 'url'=>['/admin/default/help']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Вход', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post', ['class'=>'form-inline'])
                . Html::submitButton(
                    'Выход (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout text-dark']
                )
                . Html::endForm()
                . '</li>'
            ),

        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav text-dark'],
        'items' => [
            Yii::$app->user->isGuest ? (''): (
            ['label' => '<i class="fas fa-building"></i> (' . \Yii::$app->userInfo->current_organization . ')', 'encode'=>false, 'url'=>['/admin/organization/list'], 'linkOptions'=>['class'=>'mv-link mv-no-change-url']]
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'Главная', 'url' => ['/admin/default/index']],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>        
        <?= $content ?>
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
