<?php

/** @var \yii\web\View $this */

use app\assets\FancyappsUIAsset;
use app\helpers\ImageHelper;
use yii\helpers\Url;

FancyappsUIAsset::register($this);

/** @var \app\modules\contest\modules\space\models\Space[] $models */

$index = 0;
?>

<div class="row row-cols-1 justify-content-around">
<?php foreach($models as $model): 
    $index++;
?>
    <div class="col-2 mb-5">
        <div class="card bg-transparent border-white h-100">
            <div class="card-body text-center">
                <?php foreach($model->getFiles() as $file): ?>
                    <?php if (substr($file['mime'], 0, 5) === 'video'): ?>
                        <a href="<?= $file['file'] ?>" data-fancybox data-caption="<?= $model->title ?>">
                            <video controls="" style="width:100%;">
                                <source src="<?= $file['file'] ?>">
                            </video>
                        </a>                    
                    <?php elseif (substr($file['mime'], 0, 5) === 'image'): ?>
                        <a href="<?= $file['file'] ?>" data-fancybox data-caption="<?= $model->title ?>">
                            <img src="<?= ImageHelper::findThumbnail($file['file']) ?>" class="img-thumbnail" />
                        </a>
                    <?php else: ?>                           
                            <object data="<?= $file['file'] ?>" style="width: 100%;" type="<?= $file['mime'] ?>">
                                <embed data="<?= $file['file'] ?>"></embed>
                            </object>
                            <button class="btn btn-outline-light btn-sm" data-fancybox data-src="#dialog-content_<?= $model->id ?>">
                                Подробнее
                            </button>
                            <div id="dialog-content_<?= $model->id ?>" style="display:none;" class="w-100 h-100">
                                <object data="<?= $file['file'] ?>" style="width: 100%; height: 100%;" type="<?= $file['mime'] ?>">
                                    <embed data="<?= $file['file'] ?>"></embed>
                                </object>
                            </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="card-footer border-white">
                <div class="row justify-content-between">
                    <div class="col">
                        <?= $model->title ?>
                    </div>
                    <div class="col-5">
                        <div class="float-end">                           
                            <a href="<?= Url::to(['like', 'id'=>$model->id]) ?>" class="btn btn-outline-light btn-sm btn-like" style="font-size: 1rem;">
                                <span class="badge bg-dark"><?= $model->countLike() ?></span>
                                <i class="<?= ($model->likeModel === null ? 'far' : 'fas') ?> fa-heart text-danger"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     
    <?php if ($index % 4 && $index !== count($models)): ?>
    <div class="col-1 mb-5"></div>
    <?php endif; ?>


<?php endforeach; ?>
</div>

<?php $this->registerJs(<<<JS

    Fancybox.bind("[data-fancybox]", { });

    $('.btn-like').on('click', function() {
        let btn = $(this);

        btn.find('.icon-error').remove();
        btn.append('<div class="spinner-border spinner-border-sm icon-spinner"></i>');

        $.get($(this).attr('href'))
        .done(function(data) {
            btn.find('span').html(data.count);
            let i = btn.find('.fa-heart');
            if (data.is_like) {
                i.removeClass('far');
                i.addClass('fas');
            }
            else {
                i.removeClass('fas');
                i.addClass('far');
            }
        })
        .fail(function(err) {
            console.log(err);
            btn.append('<i class="fas fa-times text-danger icon-error"></i>');
        })
        .always(function() {
            btn.find('.spinner-border').remove();
        });

        return false;
    });

JS); ?>