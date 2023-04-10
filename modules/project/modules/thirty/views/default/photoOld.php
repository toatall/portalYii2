<?php
/** @var \yii\web\View $this */
/** @var app\modules\project\modules\thirty\models\ThirtyPhotoOld[] $data */
/** @var array $orgs */

use app\assets\FancyappsUIAsset;
FancyappsUIAsset::register($this);

$this->title = 'Мгновения службы';
$this->params['breadcrumbs'][] = ['label' => 'Проекты', 'url' => ['/project']];
$this->params['breadcrumbs'][] = ['label'=>'30-летие налоговых органов', 'url'=>['/project/thirty/default/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="happy-birthday">
    
    <div class="row mv-hide">
        <div class="col border-bottom mb-2">
            <p class="display-4">
            <?= $this->title ?>
            </p>    
        </div>    
    </div>

    <div class="row row-cols-1">
        <?php foreach ($data as $item): 
            $orgName = $orgs[$item->org_code] ?? $item->org_code;
            ?>
            <div class="col-3 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center ">
                        <a href="<?= $item->getPhoto() ?>" data-fancybox="gallery" target="_blank"  data-caption="<h1 class='text-center'><?= $orgName ?></h1>">
                            <img src="<?= $item->getPhoto() ?>" class="img-thumbnail" style="height: 20vh; margin: 0 auto;" />
                        </a>
                    </div>
                    <div class="card-footer text-center">
                        <strong><?= $item->title ?></strong>
                        <hr />
                        <?= $orgName ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>       
    </div>
</div>
<?php $this->registerJs(<<<JS
    Fancybox.bind('[data-fancybox]', {});
JS); ?>