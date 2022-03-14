<?php

use app\modules\bookshelf\models\BookShelf;
use yii\bootstrap4\Html;

/** @var \yii\web\View $this */
/** @var app\modules\bookShelf\models\BookShelf $model */

?>

<div class="col-3 mb-4">
    <div class="card mb-4 shadow-sm h-100">
        <img src="<?= $model->getPhoto() ?>" class="img-thumbnail" />
        <hr class="m-0" />           
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="font-weight-bolder"><?= $model->title ?? null ?></h5>
                    <p class="lead fa-1x mb-0"><?= $model->writer ?></p>
                </div>
                <div>          
                    <span class="badge badge-dark fa-1x">  
                    <?php if ($model->rating): ?>
                        <?= Yii::$app->formatter->asDecimal($model->rating, 1) ?> 
                    <?php else: ?>
                        нет оценки
                    <?php endif; ?>
                    <i class="fas fa-star text-warning"></i>
                    </span>
                    <?php if ($model->isNewBook()): ?>
                        <span class="badge badge-success">Новая</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>      
        <div class="card-body pb-0">
            <div class="d-flex justify-content-between">
                <span title="Размещение"><i class="fas fa-map-signs text-secondary"></i> <?= $model->place ?></span>
                <?php if (BookShelf::isEditor()): ?>
                    <?php if ($model->book_status == BookShelf::STATUS_IN_STOCK): ?>                
                        <span class="badge badge-success fa-1x">В наличии</span>                                                 
                    <?php elseif ($model->book_status == BookShelf::STATUS_AWAY): ?>
                        <span class="badge badge-danger fa-1x">Нет в наличии</span>
                    <?php endif; ?>     
                <?php endif; ?> 
            </div>    
        </div>
        
        <div class="card-footer d-flex justify-content-center">
            <div class="btn-group">
                <?= Html::a('Подробнее', ['view', 'id'=>$model->id], ['class' => 'btn btn-outline-primary btn-sm mv-link']) ?>
                <?php if (BookShelf::isEditor()): ?>
                <?= Html::a('Изменить', ['update', 'id'=>$model->id], ['class' => 'btn btn-outline-secondary btn-sm mv-link']) ?>
                <?= Html::a('Удалить', ['delete', 'id'=>$model->id], [
                    'class' => 'btn btn-outline-danger btn-sm mv-link',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить?',
                        'method' => 'post',
                        'pjax' => true,
                    ],
                ]) ?>
                <?php endif; ?>
            </div>
        </div>
        
    </div>
</div>