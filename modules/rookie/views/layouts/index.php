<?php

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
    .nav-item > a.active {
        font-weight: bolder !important;
    }
CSS); ?>
<body id="page-top">
    <?php $this->beginBody() ?>
    
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
        <h5 class="my-0 mr-md-auto font-weight-normal"></h5>
        <nav class="my-2 my-md-0 mr-md-3">
            <?= Html::a('<i class="fas fa-external-link-alt"></i> Перейти на портал', ['//'], ['class'=>'p-2 text-dark']) ?>
        </nav>        
    </div>

    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        <?= $content ?>            
    </div>

    <footer class="pt-4 my-md-5 pt-md-5 border-top">
        <div class="">
            <div class="col text-center font-bolder">
                <strong>&copy; УФНС России по Ханты-Мансийскому автономному округу - Югре, <?= date('Y') ?></strong>
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>