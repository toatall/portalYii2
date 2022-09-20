<?php

/** @var \yii\web\View $this */
/** @var string $content */

use yii\bootstrap5\Html;
use app\assets\AppAsset;
use app\assets\fancybox\FancyboxAsset;
use app\assets\ModalViewerAsset;
use yii\bootstrap5\Breadcrumbs;

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

                <?= Html::a('<i class="fas fa-list"></i>&nbsp;&nbsp;Кадровые проекты', null, [
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
                    <p class="display-4 font-weight-bold">
                        Лучший профессионал
                    </p>                    
                </div>
            </div>
        </div>
        <hr />        
        
        <div class="container-fluid px-5" styles="padding-left: 20rem; padding-right: 20rem;">
            <?= Breadcrumbs::widget([
                'homeLink' => ['label' => 'Главная', 'url' => '/kadry/best-professional/'],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'options' => [
                    'class' => 'mt-2 py-2 px-4 border rounded bg-light text-decoration-none',
                ],
            ]) ?>            
            <?= $content ?>
        </div>
    </main>

    <footer class="text-muted border-top mt-4">
        <div class="container">
            <p class="float-end">
                <a href="#" class="btn btn-secondary"><i class="fas fa-arrow-up"></i></a>
            </p>
            <p>&copy; УФНС России по Ханты-Мансийскому автономному округу - Югре</p>            
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>