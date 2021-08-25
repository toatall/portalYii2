<?php
/** @var yii\web\View $this */
/** @var app\models\department\Department $model */
/** @var array $arrayCard */

use dosamigos\gallery\DosamigosAsset;
use dosamigos\gallery\GalleryAsset;

GalleryAsset::register($this);
DosamigosAsset::register($this);

$this->title = 'Структура';
$this->params['breadcrumbs'][] = ['label' => 'Отделы', 'url' => ['/department/index']];
$this->params['breadcrumbs'][] = ['label' => $model->department_name, 'url' => ['/department/view', 'id'=>$model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>

<div class="content content-color mb-4">

    <div class="col border-bottom mb-2">
        <p class="display-4">
        <?= $model->department_name . ' (структура)' ?>
        </p>    
    </div>    

    <?php if (is_array($arrayCard) && count($arrayCard) > 0): ?>
    <?php foreach ($arrayCard as $structRow): ?>
        <div class="row">
            <?php foreach ($structRow as $struct): ?>                
                <div class="col-3 mt-2" style="margin: 0 auto;">
                    <div class="card shadow-lg rounded-lg">
                        <div class="card-body">
                            <div class="gallery text-center">
                                <a href="<?= $struct['user_photo'] ?>" target="_blank" class="gallery-item">
                                    <img src="<?= $struct['user_photo'] ?>" class="img-thumbnail" style="max-width:100%; max-height: 20em; margin: 0 auto;" alt="<?= $struct['user_fio'] ?>" />
                                </a>
                            </div>
                        </div>
                        <div class="card-header" style="height: 17em; margin-top:10px; overflow: auto;">
                            <div class="text-center text-muted">
                                <h4 class="head text-uppercase" style="font-weight: bolder;"><?= $struct['user_fio'] ?></h4>
                                <p><?= $struct['user_position'] ?></p>
                                <p><?= $struct['user_rank'] ?></p>
                                <p><?= $struct['user_telephone'] ?></p>
                                <p><?= $struct['user_resp'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>            
        </div>
    <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning">Нет данных</div>
    <?php endif; ?>
</div>
<?php $this->registerJs(<<<JS
    dosamigos.gallery.registerLightBoxHandlers('.gallery a', []);
JS); ?>
