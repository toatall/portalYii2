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

$this->title = 'Фотовыставка домашних животных';
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

<body>
    <div class="position-absolute" style="left: 1rem; top: 1rem;">
        <a href="<?= Url::to(['/']) ?>" class="btn btn-outline-success btn-sm"><i class="fas fa-arrow-circle-left"></i> На портал</a>
    </div>
    <?php $this->beginBody() ?>
    <div class="text-center w-100">
        <img src="/public/content/contest/images/pets.png" style="height: 10rem;" />
    </div>
    <div class="grid text-center mt-1 border-bottom">        
        <p class="text-success fw-bold" style="font-size: 2.5rem;">
            <i class="fas fa-dog"></i> <?= Html::encode($this->title) ?>
            <i class="fas fa-cat"></i>
        </p>
    </div>

    <div class="container-fluid mt-2 pb-5 px-5">
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