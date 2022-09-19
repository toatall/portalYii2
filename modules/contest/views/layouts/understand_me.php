<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\LightGalleryAsset;
use yii\bootstrap5\Html;
use app\assets\ModalViewerAssetBs5;

LightGalleryAsset::register($this);
ModalViewerAssetBs5::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100" style="background-image: url('/public/assets/contest/understand_me/img/bg.jpg'); background-size: cover;">
<?php $this->beginBody() ?>

    <div class="grid text-center">
        <p class="stroke shadow-text">Пойми меня</p>
    </div>

    <div class="container">    
        <?php if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false): ?>
            <div class="alert alert-danger display-2 font-weight-bolder text-center" style="position: relative; z-index: 1000;">Браузер Internet Explorer не поддерживается!</div>
        <?php else: ?>
            <?= $content ?>        
        <?php endif; ?>        
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
    text-shadow: 4px 4px #96db27;
}

.halftone {
    position: relative;
}

.halftone-color:after {
  background-color: #96db27;
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
