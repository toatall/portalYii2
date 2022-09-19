<?php

/** @var yii\web\View $this */

use app\assets\AppAsset;
use yii\bootstrap5\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<?php $this->registerCSS(<<<CSS
     
    .jumbotron {
        padding-top: 3rem;
        padding-bottom: 3rem;
        margin-bottom: 0;
        background-color: #fff;
    }
    @media (min-width: 768px) {
        .jumbotron {
            padding-top: 6rem;
            padding-bottom: 6rem;
        }
    }
    .jumbotron p:last-child {
        margin-bottom: 0;
    }
    .jumbotron h1 {
        font-weight: 300;
    }
    .jumbotron .container {
        max-width: 40rem;
    }
    footer {
        padding-top: 3rem;
        padding-bottom: 3rem;
    }
    footer p {
        margin-bottom: .25rem;
    }
CSS); ?>

<body id="page-top" style="background-color: #22140b !important;">
    <?php $this->beginBody() ?>

    <header>
        <div class="navbar navbar-dark bg-dark shadow-sm">
            <div class="container col-5">
                <?= Html::a('<span class="fas fa-home"></span>&nbsp;<strong>Главная</strong>', ['/rookie/fortboyard/default/index'], ['class' => 'navbar-brand']) ?>
                <?= Html::a('<span class="fas fa-table"></span>&nbsp;<strong>На страницу проектов новобранцев</strong>', ['/rookie'], ['class' => 'navbar-brand']) ?>
                <?= Html::a('<span class="fas fa-external-link-alt"></span>&nbsp;<strong>На портал</strong>', ['/'], ['class' => 'navbar-brand']) ?>
            </div>
        </div>
    </header>
    <main role="main" style="background-color: #22140b !important;">
        <?= $content ?>
    </main>
    <footer class="text-muted" style="background-color: #22140b !important;">
        <div class="container text-center font-weight-bolder text-light">
            <p>УФНС России по Ханты-Мансийскому автономному округу - Югре &copy; <?= date('Y') ?></p>
        </div>
    </footer>
    
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>