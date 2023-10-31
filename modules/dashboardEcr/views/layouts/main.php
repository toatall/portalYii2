<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\FontAwesomeAsset;
use yii\bootstrap5\Html;
use app\assets\ModalViewerAssetBs5;
use yii\bootstrap5\Breadcrumbs;
use yii\helpers\Url;

ModalViewerAssetBs5::register($this);
FontAwesomeAsset::register($this);

$this->title = 'Миграция';
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

<body class="bg-light">
    <div class="position-absolute" style="left: 1rem; top: 1.5rem;">
        <a href="<?= Url::to(['/']) ?>" class="btn btn-outline-light btn-sm"><i class="fas fa-arrow-circle-left"></i> На портал</a>
    </div>
    <?php $this->beginBody() ?>    
    <div class="grid text-center mt-1 border-bottom bg-black">        
        <p class="fw-bold text-white pt-2" style="font-size: 2rem;">
            <i class="fas fa-dashboard"></i> <?= Html::encode($this->title) ?>
        </p>
    </div>

    <div class="container-fluid pb-5 px-5 bg-light h-100">
        <?php if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false) : ?>
            <div class="alert alert-danger display-2 font-weight-bolder text-center" style="position: relative; z-index: 1000;">Браузер Internet Explorer не поддерживается!</div>
        <?php else : ?>
            <?= Breadcrumbs::widget([
                'homeLink' => ['label' => 'Главная', 'url' => ['index']],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'options' => [
                    'class' => 'mt-2 py-2 px-4 border rounded bg-light text-decoration-none',
                ],
            ]) ?>
            <?= $content ?>
        <?php endif; ?>
    </div>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>