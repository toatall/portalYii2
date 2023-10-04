<?php

/** @var \yii\web\View $this */
/** @var \app\modules\contest\modules\pets\models\Pets[][] $models */

use app\assets\FancyappsUIAsset;
use app\modules\like\widgets\LikeWidget;
use yii\bootstrap5\Html;
use yii\helpers\Url;

FancyappsUIAsset::register($this);

?>

<div class="row justify-content-center">
    <div class="col-10 mb-3">
        <div class="list-group d-flex flex-row flex-wrap">
            <?php foreach($models as $dep => $model): ?>
            <a href="#<?= md5($dep) ?>" class="list-group-item w-50 list-group-item-action"><?= $dep ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="mb-2">
    <?php if (Yii::$app->user->can('admin')): ?>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    <?php endif; ?>
</div>


<?php foreach($models as $depName => $modelsPets): ?>
    <div class="card mb-3">
        <div class="card-header">
            <h4 id="<?= md5($depName) ?>"><?= $depName ?></h4>
        </div>
        <div class="card-body">
            <div class="row">        
        
            <?php foreach($modelsPets as $model): ?>
                <div class="col-3">
                    <div class="card shadow" style="background-color: rgba(0, 200, 0, .07);">
                        <div class="card-header">
                            <b><?= $model->pet_name ?></b>                   
                        </div>                        
                        <div class="card-body text-center">
                            <div class="carousel slide" id="carousel_<?= $model->id ?>" data-bs-ride="carousel">
                                <div class="carousel-inner gallery">
                                    <?php if ($images = $model->getFiles()):
                                    $active = false;
                                    foreach($images as $image):                                 
                                    ?>                                    
                                        <div class="carousel-item <?= $active ? '' : 'active' ?>">                                            
                                            <a href="<?= $image ?>" data-fancybox target="_blank" class="gallery-item" 
                                                data-fancybox data-caption='<div class="text-center"><h3><?= $model->pet_name ?></h3><p><?= $model->pet_note ?></p></div>'>
                                                <img src="<?= $image ?>" class="img-thumbnail" style="max-width:100%; height: 20rem; margin: 0 auto;" />
                                            </a>
                                        </div>                                
                                    
                                    <?php 
                                        $active = true;
                                    endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carousel_<?= $model->id ?>" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carousel_<?= $model->id ?>" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                        
                        <div class="card-body text-center">
                            <?= $model->owner->fio ?? $model->pet_owner ?>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <?= Html::button('Подробнее', ['class' => 'btn-note btn btn-success']) ?>
                            <?php if (Yii::$app->user->can('admin')): ?>
                                <?= Html::a('<i class="fas fa-pencil"></i>', ['update', 'id' => $model->id], ['class' => 'btn btn-success', 'title' => 'Редактировать']) ?>
                            <?php endif; ?>
                            <?= Html::button('<icon><i class="' . ($model->isLike() ? 'fas' : 'far') .' fa-heart"></i></icon> <span class="count">' . $model->countLikes() . '</span>', [
                                'class' => 'btn btn-success btn-like',
                                'data-url' => Url::to(['like', 'id' => $model->id]),
                            ]) ?>
                        </div>
                        <div class="card-body note" style="display: none;">
                            <?= (empty($model->pet_note) ? 'Описания нет' : $model->pet_note) ?>
                        </div>
                    </div>
                </div>
            
            <?php endforeach; ?>

            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php $this->registerJs(<<<JS

    Fancybox.bind("[data-fancybox]", { })
    
    $('.carousel').each(function() {
        let c = new bootstrap.Carousel($(this))       
    })

    $('.btn-note').on('click', function() {
        $(this).parent('div').next('div.note').toggle()
    })

    $('.btn-like').on('click', function() {
        let btn = $(this)
        $(this).html($(this).html() + ' <span class="spinner-border spinner-border-sm"></span>')
        $(this).prop('disabled', true)
        $.get($(this).data('url'))
        .done(function(data) {
            btn.find('.count').html(data.count)
            if (data.isLike) {
                btn.find('icon').html('<i class="fas fa-heart"></i>')
            }
            else {
                btn.find('icon').html('<i class="far fa-heart"></i>')
            }
        })
        .always(function() {
            btn.prop('disabled', false)
            btn.find('.spinner-border').remove()
        })
        return false
    })

JS); ?>