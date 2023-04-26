<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\FancyappsUIAsset;
use yii\bootstrap5\Html;

FancyappsUIAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="32x32" href="/public/assets/contest/photo-kids/img/pngegg.png">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100" style="background-image: url('/public/assets/contest/photo-kids/img/v957-wan-15.jpg'); background-size: cover;">
<?php $this->beginBody() ?>

    <div class="grid text-center">
        <p class="stroke shadow-text">Все мы родом из детства</p>
    </div>

    <div class="container">    
        <?= $content ?>        
    </div>

<?php 
$this->registerCss(<<<CSS

.stroke {
  -webkit-text-stroke-width: 2px;
  -moz-text-stroke-width: 2px;
  -webkit-text-stroke-color: #111827;
  -moz-text-stroke-color: #111827;
  color: transparent;
}

.shadow-text {
  text-shadow: 6px 6px #db2777;
}

.halftone {
  position: relative;
}

.halftone-color:after {
  background-color: #db2777;
}


body p {
  font-size: 5vw;
  font-weight: bold;
  letter-spacing: 5px;
  margin: 0;
}
body .grid {
  display: grid;
  grid-gap: 5vw;
  grid-template-columns: 1fr;
}

CSS); ?>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
