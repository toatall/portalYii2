<?php

use app\assets\AppAsset;
use app\assets\ModalViewerAssetBs5;
use yii\bootstrap5\BootstrapPluginAsset;
use yii\bootstrap5\Html;

AppAsset::register($this);
ModalViewerAssetBs5::register($this);
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

    <main>   
    
        <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
            <h5 class="my-0 mr-md-auto font-weight-normal"></h5>
            <nav class="my-2 my-md-0 mr-md-3">
                <?= Html::a('<i class="fas fa-arrow-alt-circle-left"></i> На портал', ['//'], ['class'=>'btn btn-secondary fw-bold']) ?>
            </nav>        
        </div>

        <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto">
            <div class="container">
                <?= $content ?>
            </div>
        </div>

        <footer class="pt-4 pb-4 pt-md-5 border-top bg-light position-absolute bottom-0 w-100">
            <div class="col text-center font-bolder">
                <strong>&copy; УФНС России по Ханты-Мансийскому автономному округу - Югре, <?= date('Y') ?></strong>
                <br /><small>Введено в эксплуатацию: 07.03.2023</small>
            </div>
        </footer>

    </main>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>