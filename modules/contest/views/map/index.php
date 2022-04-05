<?php

/** @var \yii\web\View $this */
/** @var array $regions */
/** @var array $cities */
/** @var array $missionToday */
/** @var array $missionAll */
/** @var array $isAnswered */

use yii\bootstrap4\Html;
use app\assets\AppAsset;
use app\assets\fancybox\FancyboxAsset;
use app\assets\ModalViewerAsset;

FancyboxAsset::register($this);
AppAsset::register($this);
ModalViewerAsset::register($this);
$this->title = 'УДИВИТЕЛЬНАЯ РОССИЯ';
$this->registerCssFile('public/assets/kadry/css/album.css', [
    'depends' => AppAsset::class,
]);
$this->registerJs(<<<JS
    $('.gallery').fancybox();
JS);
$this->registerCss(<<<CSS
    body, main, footer {
        background-color: #5cc4bb;
    }
    .card-header-bg {
        background-image: url('/public/content/map/images/4594795_rus_ornament_6.png');
        color: #eb4c1e;
        text-shadow: 2px 0 0 #fff, -2px 0 0 #fff, 0 2px 0 #fff, 0 -2px 0 #fff, 1px 1px #fff, -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff;
    }
    .card-body-bg {
        background-color: rgba(246,188,104,0.4);
    }
    .card-body-bg-2 {
        background-color: rgba(92,196,187,0.6);
    }
    .modal-header {
        background-image: url('/public/content/map/images/1.jpg');
        background-size: contain;
        text-shadow: 2px 0 0 #fff, -2px 0 0 #fff, 0 2px 0 #fff, 0 -2px 0 #fff, 1px 1px #fff, -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff;
    }
    .modal-footer {
        background-image: url('/public/content/map/images/42584456.jpg');
        background-size: contain;
        text-shadow: 2px 0 0 #fff, -2px 0 0 #fff, 0 2px 0 #fff, 0 -2px 0 #fff, 1px 1px #fff, -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff;
    }    
    svg {        
        width: 98vw;
        height: 100vh;
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
            <div class="container d-flex justify-content-between">
                <?= Html::a('<i class="fas fa-arrow-left"></i>&nbsp;&nbsp;На Портал Управления', ['//'], [
                    'class' => 'navbar-brand d-flex align-items-center font-weight-bolder',
                    'style' => 'font-size: 16px;'
                ]) ?>
            </div>
        </div>
    </header>

    <main role="main">
        <div class="container">
            <div class="row">
                <div style="height: 90px; margin-right:10px;">                    
                    <img src="/public/content/map/images/pngwing.com.png" style="height:100%;" />
                </div>
                <div style="width: 253px;">                    
                    <img src="/public/assets/kadry/img/Service-FNS-Logo-B.png" />
                </div>
                <div class="col text-center mt-1">
                    <p class="fa-3x font-weight-bold" style="text-shadow: 2px 2px #eb4c1e; color: #f6bc68">
                        <?= $this->title ?>
                    </p>                    
                </div>
            </div>
        </div>
        <hr />        
        
        <div class="container-fluid">
            <?= $this->render('_map', [
                'regions' => $regions,
                'cities' => $cities,
                'missionToday' => $missionToday,
                'missionAll' => $missionAll,
                'isAnswered' => $isAnswered,
            ]) ?>
        </div>
    </main>

    <hr />
    <footer class="text-muted mt-4">
        <div class="container">
            <p class="float-right">
                <a href="#" class="btn btn-secondary"><i class="fas fa-arrow-up"></i></a>
            </p>
            <p class="text-white font-weight-bolder">&copy; УФНС России по Ханты-Мансийскому автономному округу - Югре</p>            
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>