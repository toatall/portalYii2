<?php

/** @var \yii\web\View $this */
/** @var string $content */

use app\assets\AnimateCssAsset;
use yii\bootstrap5\Html;
use app\assets\AppAsset;
use app\assets\fancybox\FancyboxAsset;
use app\assets\ModalViewerAssetBs5;

AnimateCssAsset::register($this);
FancyboxAsset::register($this);
AppAsset::register($this);
ModalViewerAssetBs5::register($this);
$this->registerCssFile('/public/assets/kadry/css/album.css', [
    'depends' => AppAsset::class,
]);

$this->registerJs(<<<JS
    $('.gallery').fancybox();
JS);
$this->registerCss(<<<CSS
    body {
        background-image: url('/public/content/bookshelf/images/fon-knigi-risunki-10595.jpeg');
        background-size: contain;
        background-attachment: fixed;
    }
    .breadcrumb a, .breadcrumb li {
        color: white;        
    }    
CSS);
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

    <header>
        <div class="navbar navbar-dark bg-dark shadow-sm">
            <div class="container-fluid d-flex justify-content-between">
                <?= Html::a('<i class="fas fa-arrow-left"></i>&nbsp;&nbsp;На Портал Управления', ['//'], [
                    'class' => 'navbar-brand d-flex align-items-center font-weight-bolder',
                    'style' => 'font-size: 16px;'
                ]) ?>                
            </div>
        </div>
    </header>

    <main role="main" style="overflow: hidden;">
        <div class="container-fluid">
            <div class="row">                
                <div class="col text-center mt-1">
                    <div class="row justify-content-center">
                        <div class="animate__animated animate__slideInLeft">
                            <img src="/public/content/bookshelf/images/noroot.png" class="animate__animated animate__rotateIn" style="height: 6rem;" />
                        </div>
                        <p class="display-3 font-weight-bolder animate__animated animate__slideInRight" id="main-title" style="text-shadow: 2px 2px #f5e5e0;color: #b77104;">
                            Книжная полка
                        </p>
                    </div>
                </div>                                                            
            </div>
        </div>

        <hr />        
        
        <div class="container-fluid pl-5 pr-5">
            <?php /* if (isset($this->params['breadcrumbs']) && $this->params['breadcrumbs']): ?>
            <?= Breadcrumbs::widget([
                'homeLink' => false,
                'links' => $this->params['breadcrumbs'],
                'options' => [
                    'class' => 'bg-dark text-white',                  
                ],
            ]) ?>            
            <?php endif;*/ ?>
            <?= $content ?>
        </div>
        
    </main>

    <footer class="text-muted border-top border-secondary mt-4">
        <div class="container">
            <p class="float-end">
                <a href="#" class="btn btn-secondary"><i class="fas fa-arrow-up"></i></a>
            </p>
            <p class="text-white lead font-weight-bolder" style="text-shadow: 2px 2px #222;">&copy; УФНС России по Ханты-Мансийскому автономному округу - Югре</p>            
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>