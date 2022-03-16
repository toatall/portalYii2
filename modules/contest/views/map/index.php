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
$this->registerCssFile('public/assets/kadry/css/album.css', [
    'depends' => AppAsset::class,
]);
$this->registerJs(<<<JS
    $('.gallery').fancybox();
JS);

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
                <div style="width: 253px;">
                    <img src="/public/assets/kadry/img/Service-FNS-Logo-B.png" />
                </div>
                <div class="col text-center mt-1">
                    <p class="fa-2x font-weight-bold">
                        Название конкурса
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

    <footer class="text-muted border-top mt-4">
        <div class="container">
            <p class="float-right">
                <a href="#" class="btn btn-secondary"><i class="fas fa-arrow-up"></i></a>
            </p>
            <p>&copy; УФНС России по Ханты-Мансийскому автономному округу - Югре</p>            
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>