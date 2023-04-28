<?php

/** @var yii\web\View $this */
/** @var yii\data\ArrayDataProvider $dataProviderBooks */
/** @var app\modules\bookshelf\models\BookShelfCalendar[]|null $modelCalendarToday */
/** @var app\modules\bookshelf\models\WhatReading[]|null $modelLastWhatReading */
/** @var app\modules\bookshelf\models\BookShelfDiscussion[]|null $modelLastDiscussion */
/** @var array $discussions */
/** @var app\modules\bookshelf\models\RecommendRead[]|null $recommend */

use app\modules\bookshelf\models\BookShelf;
use yii\bootstrap5\Html;
use yii\widgets\Pjax;

$this->title = 'Книжная полка';

$this->registerCss(<<<CSS
    .card::hover {
        opacity: 1 !important;
    }
    .card {
        opacity: 1;
    }
CSS);

?>

<div class="book-shelf-index">

    <div class="row">
        <div class="col-6">                        
                        
            <div class="card bg-dark text-white shadow-lg animate__animated animate__backInLeft animate__slow animate__delay-1s">
                <div class="card-header mb-2 text-white text-uppercase">
                    <strong><i class="fas fa-book"></i> Книги</strong>
                </div>
                <div class="card-body">
                    <?php Pjax::begin(['id'=>'pjax-bookshelf-default-books', 'enablePushState'=>false, 'timeout'=>false]) ?>
                    <?php $model = count($dataProviderBooks->getModels()) > 0 ? $dataProviderBooks->getModels()[0] : null; ?>
                    <?php if ($model != null):
                        /** @var BookShelf $model */    
                    ?>
                    <div class="row">
                        <div class="align-self-center text-center pr-3 pl-3" style="width: 8rem;">
                            <?php if (isset($dataProviderBooks->pagination->links['prev'])): ?>
                                <?= Html::a('<i class="fas fa-arrow-left"></i>', 
                                    $dataProviderBooks->pagination->links['prev'], 
                                    ['class' => 'fa-2x btn btn-dark']) ?>
                            <?php endif ?>
                        </div>
                        <div class="col p-0">
                            <div>
                                <div class="text-center mt-2">
                                    <img src="<?=$model->getPhoto() ?>" class="img-thumbnail" style="max-height: 30rem;" />
                                </div>                                     
                                <div class="mt-3 mb-3 text-center">
                                    <h5 class="font-weight-bolder"><?= $model->title ?? null ?></h5>
                                    <h5><?= $model->writer ?></h5>
                                    <hr class="bg-light" />
                                    <div class="row">
                                        <div class="col">
                                            <span class="badge badge-dark fa-1x">  
                                            <?php if ($model->rating): ?>
                                                <?= Yii::$app->formatter->asDecimal($model->rating, 1) ?> 
                                            <?php else: ?>
                                                нет оценки
                                            <?php endif; ?>
                                            <i class="fas fa-star text-warning"></i>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <span class="badge badge-dark fa-1x">  
                                            <?php $comments = $model->comments; ?>
                                            <?= count($comments) ? count($comments) : 'нет комментариев' ?>
                                            <i class="fas fa-comment-alt text-light"></i>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    
                                    <hr class="bg-light" />
                                    <p class="text-justify">
                                        <?= $model->description ?>
                                    </p>
                                    <?= Html::a('Подробнее о книге...', ['/bookshelf/book/view', 'id'=>$model->id], ['class' => 'btn btn-secondary btn-sm mv-link']) ?>
                                </div>
                            </div>
                        </div>
                        <div class="align-self-center text-center pr-3 pl-3" style="width: 8rem;">
                            <?php if (isset($dataProviderBooks->pagination->links['next'])): ?>
                                <?= Html::a('<i class="fas fa-arrow-right"></i>', 
                                $dataProviderBooks->pagination->links['next'], 
                                ['class' => 'fa-2x btn btn-dark btn-round']) ?>
                            <?php endif ?>
                        </div>
                    </div> 

                    <?php else: ?>                    
                        <p class="lead text-white">Нет данных</p>
                    <?php endif; ?>

                    <?php Pjax::end() ?>
                    
                </div> 
                <div class="card-footer">
                    <?= Html::a('<i class="fas fa-angle-right"></i> Все книги', ['/bookshelf/book/index'], ['class' => 'btn btn-secondary btn-lg']) ?>              
                </div>                                                      
            </div>            
                                                    
        </div>                                

        <div class="col animate__animated animate__backInRight animate__slow animate__delay-1s">
            
            <div class="card bg-dark">
                <div class="card-header mb-2 text-white text-uppercase">
                    <strong><i class="fas fa-calendar"></i> Календарь литературных дат</strong>
                </div>
                <div class="card-body">
                    <?php if (!empty($modelCalendarToday) && is_array($modelCalendarToday)): ?>
                        <?php foreach ($modelCalendarToday as $model): ?>
                            <div class="bg-dark border border-light rounded p-3 mb-2 text-white" data-toggle="popover" title="<?= $model->writer ?>" data-content="<p class='text-justify'><?= $model->description ?></p>" data-trigger="hover" data-html="true">
                                <div class="row">
                                    <div class="ml-3" style="width: 10rem !important;">
                                        <img class="card-img-top img-thumbnail" src="<?= $model->getPhoto() ?>" alt="<?= $model->writer ?>">
                                    </div>
                                    <div class="col">
                                        <h2 class="card-title"><?= $model->writer ?></h2>
                                        <p class="card-text">
                                            (<?= Yii::$app->formatter->asDate($model->date_birthday) ?>
                                                -
                                            <?= Yii::$app->formatter->asDate($model->date_die) ?>)    
                                        </p>
                                    </div>
                                </div>
                            </div>                         
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-secondary">Нет данных</div>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <?= Html::a('<i class="fas fa-angle-right"></i> Просмотр всех дат', ['/bookshelf/calendar/index'], ['class' => 'btn btn-secondary btn-lg']) ?>
                </div>
            </div>

            <div class="card bg-dark mt-4">
                <div class="card-header mb-2 text-white">
                    <strong class="text-uppercase" style="font-size: 1.5rem;">
                        <i class="fas fa-comments"></i> 
                            Активных дискуссий 
                        <span class="badge badge-success fa-1x">
                            <?= count($discussions) ?>
                        </span>                        
                    </strong>                    
                    <br />
                    <?= Html::a('Подробнее', ['/bookshelf/book/discussions'], ['class' => 'btn btn-outline-light btn-sm mv-link mt-2']) ?>                   
                </div>               
            </div>

            <div class="card bg-dark mt-4">
                <div class="card-header mb-2 text-white text-uppercase">
                    <strong><i class="fas fa-book-reader"></i> Кто что читает</strong>
                </div>
                <div class="card-body">
                    <?php if (!empty($modelLastWhatReading) && is_array($modelLastWhatReading)): ?>
                        <?php foreach ($modelLastWhatReading as $model): ?>
                            <div class="border border-light p-2 text-light">
                                <div class="row">
                                    <div style="width: 9rem;" class="text-center">
                                        <?php
                                        $img = $model->getImage();
                                        if ($img):
                                        ?>
                                            <?= Html::img($img, ['class' => 'img-thumbnail m-1', 'style' => 'width: 7rem']) ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="lead col pl-2">
                                        <strong><?= $model->fio ?></strong><br />
                                        <?= $model->title ?> (<?= $model->writer ?>)
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-secondary">Нет данных</div>
                    <?php endif; ?>
                </div>
                <?php if (BookShelf::isEditor()): ?>
                <div class="card-footer">
                    <?= Html::a('<i class="fas fa-angle-right"></i> Все записи', ['/bookshelf/what-reading/index'], ['class' => 'btn btn-secondary btn-lg']) ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="card bg-dark mt-4">
                <div class="card-header mb-2 text-white text-uppercase">
                    <strong><i class="fas fa-book-open"></i> Рекомендации к прочтению</strong>
                </div>
                <div class="card-body text-white">
                    <?php if (!empty($recommend) && is_array($recommend)): ?>
                        <?php foreach ($recommend as $model): ?>
                            <div class="border border-light p-2 text-light">    
                                <div style="font-size: 1.15rem;">                         
                                    <strong><?= $model->fio ?></strong><br />
                                    <i><?= $model->book_name ?> (<?= $model->writer ?>)</i>
                                </div>
                                <hr class="bg-light" />
                                <?= $model->description ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-secondary">Нет данных</div>
                    <?php endif; ?>
                </div>
                <?php if (BookShelf::isEditor()): ?>
                <div class="card-footer">
                    <?= Html::a('<i class="fas fa-angle-right"></i> Все записи', ['/bookshelf/recommend-read/index'], ['class' => 'btn btn-secondary btn-lg']) ?>
                </div>
                <?php endif; ?>
            </div>
            
        </div>        
    </div>
</div>