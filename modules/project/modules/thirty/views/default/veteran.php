<?php
/** @var \yii\web\View $this */
/** @var \app\models\thirty\ThirtyVeteran[][] $data */
/** @var array $orgs */

use yii\bootstrap5\Html;

use app\assets\FancyappsUIAsset;
FancyappsUIAsset::register($this);

$this->title = 'Поздравление ветеранов и заслуженных работников!';
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

    <?php foreach ($data as $org => $items): ?>
    <div class="card mb-4">
        <div class="card-header">
            <p class="lead fs-3"><?= $orgs[$org] ?? $org ?></p>
        </div>
        <div class="card-body">
            <div class="row row-cols-5 g-4">
            <?php foreach ($items as $item): ?>
                <div class="col mb-2">
                    <div class="card h-100">
                        <?= Html::a(Html::img($item->getThumb(), [
                            'class'=>'card-img rounded-0', 
                            'data-fancybox'=>'gallery',
                            'data-caption' => "<h1 class='text-center'>" . $item->fio . "</h1>",
                        ]), $item->getPhoto(), [
                            'target'=>'_blank',                        
                            'class' => 'align-self-stretch',
                        ]) ?>
                        <div class="card-footer h-100 text-center d-flex justify-content-center">
                            <span class="align-self-center lead">
                                <?= $item->fio ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php $this->registerJs(<<<JS
    Fancybox.bind('[data-fancybox]', {});
JS); ?>