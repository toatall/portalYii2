<?php
/** @var \yii\web\View $this */
/** @var app\models\thirty\ThirtyThroughTime[] $model */

use app\assets\FancyappsUIAsset;
use yii\bootstrap5\Html;

FancyappsUIAsset::register($this);

$this->title = 'Сквозь время';
$this->params['breadcrumbs'][] = ['label' => 'Проекты', 'url' => ['/project']];
$this->params['breadcrumbs'][] = ['label'=>'30-летие налоговых органов', 'url'=>['/project/thirty/default/index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="through-time">
    
    <div class="row mv-hide">
        <div class="col border-bottom mb-2">
            <p class="display-4">
            <?= $this->title ?>
            </p>    
        </div>    
    </div>

    <div class="row row-cols-1">
        <?php foreach ($model as $item): ?>
        <div class="col-6 mb-3">
            <div class="h-100">
                <div class="card-group h-100">                    
                    <div class="col-6 card h-100">
                        <div class="card-body text-center">
                            <a href="<?= $item->getPhotoOld() ?>" data-fancybox="gallery" data-caption="<div class='text-center'><h1><?= Html::encode($item->old_photo_title) ?></h1><?= $item->org_code ?></div>">
                                <img src="<?= $item->getPhotoOld() ?>" class="img-thumbnail" style="height: 20vh; margin: 0 auto;" />
                            </a>
                        </div>
                        <div class="card-footer text-center">
                            <strong><?= $item->old_photo_title ?></strong><br />
                            <?= $item->org_code ?>
                        </div>
                    </div>                    
                    <div class="col-6 card h-100">                        
                        <div class="card-body text-center">
                            <a href="<?= $item->getPhotoNew() ?>" data-fancybox="gallery" data-caption="<div class='text-center'><h1><?= Html::encode($item->new_photo_title) ?></h1><?= $item->org_code ?></div>">
                                <img src="<?= $item->getPhotoNew() ?>" class="img-thumbnail" style="height: 20vh; margin: 0 auto;" />
                            </a>
                        </div>
                        <div class="card-footer text-center">
                            <strong><?= $item->new_photo_title ?></strong><br />
                            <?= $item->org_code ?>
                        </div>
                    </div>
                </div>                   
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php $this->registerJs(<<<JS
    Fancybox.bind('[data-fancybox]', {});
JS); ?>