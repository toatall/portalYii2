<?php
/* @var $this \yii\web\View */
/* @var $model array */

use dosamigos\gallery\GalleryAsset;
GalleryAsset::register($this);

$this->title = 'А нам тоже 30 лет!';
$this->params['breadcrumbs'][] = ['label'=>'30-летие налоговых органов', 'url'=>['/thirty/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="through-time">
<h1 class="head mv-hide"><?= $this->title ?></h1>

    <div class="row" style="padding: 20px;">
        <?php
        /*
            $items = [];
            foreach ($model as $item) {
                $items[] = [
                    'url' => $item['photo'],
                    'options' => ['title' => $item['description'] . '. ' . $item['code_ifns']],
                    'src' => $item['photo'],
                    'imageOptions' => [
                        'class' => 'img-thumbnail',
                        'style' => 'height: 20vh; margin: 0 auto;',
                    ],
                ];
            }

        ?>
        <?= \dosamigos\gallery\Gallery::widget(['items' => $items]);*/ ?>

        <div class="row gallery">
            <?php foreach ($model as $item): ?>
                <div class="col-sm-3 gallery-item">
                    <div class="panel panel-default">
                        <div class="panel-body text-center ">
                            <a href="<?= $item['photo'] ?>" class="gallery-link"  data-caption="<h1><?= $item['description'] ?></h1><?= $item['code_ifns'] ?>">
                                <img src="<?= $item['photo'] ?>" class="thumbnail" style="height: 20vh; margin: 0 auto;" />
                            </a>
                        </div>
                        <div class="panel-footer">
                            <strong><?= $item['description'] ?></strong><br />
                            <?= $item['code_ifns'] ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php $this->registerJS(<<<JS
    $('.gallery-link').on('click', function() {
        blueimp.Gallery($(this));
        return false;
    }); 
JS
);
?>