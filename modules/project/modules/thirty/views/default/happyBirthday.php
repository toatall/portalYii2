<?php
/** @var \yii\web\View $this */
/** @var app\modules\project\modules\thirty\models\ThirtyHappyBirthday[] $data */
/** @var string $orgs */

use app\assets\FancyappsUIAsset;

FancyappsUIAsset::register($this);

$this->title = 'А нам тоже 30 лет!';
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

    <?php foreach ($data as $orgCode => $items): ?>
        <div class="mb-4">
            <div class="card">
                <div class="card-header">
                    <h4>
                        <?= $orgs[$orgCode] ?? $orgCode ?>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                    <?php foreach ($items as $item):                        
                        ?>
                        <div class="col-3 mb-2 gallery-item card-deck">
                            <div class="card">
                                <div class="card-body text-center">
                                    <a href="<?= $item->getPhoto() ?>" data-fancybox="gallery"  data-caption="<h1 class='text-center'><?= $item->fio_full ?></h1>">
                                        <img src="<?= $item->getThumb() ?>" class="img-thumbnail" style="height: 20vh; margin: 0 auto;" />
                                    </a>
                                </div>
                                <div class="card-footer text-center">
                                    <span class="lead"><?= $item->fio_full ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
       
<?php $this->registerJs(<<<JS
    Fancybox.bind('[data-fancybox]', {});
JS); ?>
