<?php

use app\assets\FancyappsUIAsset;
use yii\helpers\Html;

FancyappsUIAsset::register($this);

/** @var yii\web\View $this */
/** @var app\modules\kadry\modules\beginner\models\Beginner $model */

?>
<div class="beginner-view">

    <?= $model->description ?>
    
    <hr />

    <?php foreach($model->getGalleryImages() as $image): ?>
        <a href="<?= $image ?>" data-fancybox="gallery" data-caption="<div class='text-center'><h1><?= Html::encode($model->fio) ?></h1><?= $model->departmentModel->department_name ?></div>">
            <img src="<?= $image ?>" class="img-thumbnail" style="height: 20vh; margin: 0 auto;" />
        </a>
    <?php endforeach; ?>

</div>

<?php $this->registerJs(<<<JS
    Fancybox.bind('[data-fancybox]', {});
JS); ?>