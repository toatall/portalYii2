<?php

/** @var yii\web\View $this */
/** @var string $content */

use yii\bootstrap5\Html;
use app\modules\events\assets\ContestAsset;
use yii\helpers\Url;
use app\assets\ModalViewerAssetBs5;

ContestAsset::register($this);
ModalViewerAssetBs5::register($this);

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
<body id="page-top">
<?php $this->beginBody() ?>
<!-- Navigation-->
<nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand js-scroll-trigger text-warning" href="<?= Url::to(['index']) ?>">Конкурс "Навстречу искусству"</a>
        <button class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item mx-0 mx-lg-1">                    
                    <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="<?= Url::to(['/site/index']) ?>">
                        Вернуться&nbsp;на&nbsp;портал&nbsp;
                    </a>
                </li>
                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#main">Главная</a></li>
                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#gallery">Галерея</a></li>
                <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#winner">Правильно&nbsp;ответили</a></li>
                <?php if (Yii::$app->user->can('events.contestAtrs') || Yii::$app->user->can('admin')): ?>
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="<?= Url::to(['/events/contest-arts/admin']) ?>">Управленеие</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<?= $content ?>

<!-- Copyright Section-->
<div class="copyright py-4 text-center text-white">
    <div class="container">УФНС России по Ханты-Мансийскому автономному округу - Югре, 2021</div>
</div>
<!-- Scroll to Top Button (Only visible on small and extra-small screen sizes)-->
<div class="scroll-to-top d-lg-none position-fixed">
    <a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top"><i class="fa fa-chevron-up"></i></a>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
