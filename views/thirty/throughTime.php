<?php
/** @var \yii\web\View $this */
/** @var array $model */


use dosamigos\gallery\DosamigosAsset;
use dosamigos\gallery\GalleryAsset;

GalleryAsset::register($this);
DosamigosAsset::register($this);

$this->title = 'Сквозь время';
$this->params['breadcrumbs'][] = ['label'=>'30-летие налоговых органов', 'url'=>['/thirty/index']];
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

    <div class="row gallery">
        <?php foreach ($model as $item): ?>
        <div class="col-6 card-deck mb-2">
            <div class="card card-body">
                <div class="row card-deck">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <a href="<?= $item['photo_old'] ?>" class="gallery-item" data-caption="<h1><?= $item['description_old'] ?></h1><?= $item['code_ifns'] ?>">
                                    <img src="<?= $item['photo_old'] ?>" class="img-thumbnail" style="height: 20vh; margin: 0 auto;" />
                                </a>
                            </div>
                            <div class="card-footer text-center">
                                <strong><?= $item['description_old'] ?></strong><br />
                                <?= $item['code_ifns'] ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <a href="<?= $item['photo_new'] ?>" class="gallery-item" data-caption="<h1><?= $item['description_new'] ?></h1><?= $item['code_ifns'] ?>">
                                    <img src="<?= $item['photo_new'] ?>" class="img-thumbnail" style="height: 20vh; margin: 0 auto;" />
                                </a>
                            </div>
                            <div class="card-footer text-center">
                                <strong><?= $item['description_new'] ?></strong><br />
                                <?= $item['code_ifns'] ?>
                            </div>
                        </div>
                    </div>
                </div>                   
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>


<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>
<?php $this->registerJs(<<<JS
    dosamigos.gallery.registerLightBoxHandlers('.gallery a', []);
JS); ?>