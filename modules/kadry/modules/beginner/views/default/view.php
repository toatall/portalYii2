<?php

use app\assets\FancyappsUIAsset;
use yii\helpers\Html;
use app\helpers\ImageHelper;
use app\modules\kadry\modules\beginner\models\Beginner;
use yii\widgets\DetailView;

FancyappsUIAsset::register($this);

/** @var yii\web\View $this */
/** @var app\modules\kadry\modules\beginner\models\Beginner $model */

if ($model->js) {
    $this->registerJs($model->js);
}
if ($model->css) {
    $this->registerCss($model->css);
}
?>
<div class="beginner-view">

    <div class="row">
        <?php if (($thumb = $model->getThumbImage())): ?>
        <div class="col-2">
            <a href="<?= $thumb ?>" data-fancybox="thumbnail" data-caption="<div class='text-center'><h1><?= Html::encode($model->fio) ?></h1>">
                <?= Html::img(ImageHelper::findThumbnail($thumb), ['class' => 'img-thumbnail']) ?>
            </a>
        </div>
        <?php endif; ?>
        <div class="col">
        <?= $model->description ?>
        </div>
    </div>      
    
       
    <?php if (($gallery = $model->getGalleryImages())): ?>
        <div class="card mt-2">
            <div class="card-header">
                <?= $model->getAttributeLabel('filesUpload') ?>
            </div>
            <div class="card-body">
                <?php foreach($gallery as $image): ?>
                    <a href="<?= $image ?>" data-fancybox="gallery" data-caption="<div class='text-center'><h1><?= Html::encode($model->fio) ?></h1><?= $model->departmentModel->department_name ?></div>">
                        <img src="<?= ImageHelper::findThumbnail($image, picImageNotFound: '/img/no_image_available.jpeg') ?>" class="img-thumbnail" style="height: 20em; margin: 0 auto;" />
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (Beginner::isRoleModerator()): ?>
    <div class="card mt-4 small">
        <div class="card-header">
            <a href="#system-information" class="text-decoration-none" data-bs-toggle="collapse" role="button">               
                Системная ифнормация
            </a>
        </div>
        <div id="system-information" class="card-body collapse">               
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'date_create:datetime',
                    'date_update:datetime',
                    'author',
                    'authorModel.fio:text:Автор (ФИО)'
                ],                    
            ]) ?>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="mt-4">
        <?= app\modules\comment\widgets\CommentWidget::widget([
            'modelName' => Beginner::class,
            'modelId' => $model->id,
        ]) ?>
    </div>

</div>

<?php $this->registerJs(<<<JS
    Fancybox.bind('[data-fancybox="thumbnail"]', {});
    Fancybox.bind('[data-fancybox="gallery"]', {});
JS); ?>