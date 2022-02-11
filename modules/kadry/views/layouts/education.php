<?php

/** @var \yii\web\View $this */
/** @var string $content */

use yii\bootstrap4\Html;
use app\assets\AppAsset;
use app\assets\ModalViewerAsset;
use yii\bootstrap4\Breadcrumbs;

AppAsset::register($this);
ModalViewerAsset::register($this);
$this->registerCssFile('public/assets/kadry/css/album.css', [
    'depends' => AppAsset::class,
])
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
                    <p class="fa-2x font-weight-bold">
                        Комплекс программ профессионального развития в рамках цифровой трансформации
                    </p>
                    <h5 class="lead fa-2x"><i>Новые вызовы - новые умения!</i></h5>
                </div>
            </div>
        </div>
        <hr />        
        
        <div class="container">
            <?= Breadcrumbs::widget([
                'homeLink' => ['label' => 'Главная', 'url' => '/kadry/education/'],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>            
            <?= $content ?>
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