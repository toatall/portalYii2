<?php

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookShelf[]|null $modelLastBooks */
/** @var app\modules\bookshelf\models\BookShelfCalendar[]|null $modelCalendarToday */
/** @var app\modules\bookshelf\models\WhatReading[]|null $modelLastWhatReading */
/** @var app\modules\bookshelf\models\BookShelfDiscussion[]|null $modelLastDiscussion */


use yii\bootstrap4\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

$this->title = 'Книжная полка';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="book-shelf-index">
    <p class="display-4 border-bottom">
        <?= Html::encode($this->title) ?>
    </p>


    <div class="row">   
        <div class="col">            
            <div class="card">
                <div class="card-header mb-2">
                    <strong><i class="fas fa-book-open"></i> Последние книги</strong>
                </div>
                <div class="card-body">
                    <?php if ($modelLastBooks && is_array($modelLastBooks)): ?>
                        <?php foreach($modelLastBooks as $model): ?>
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="row">
                                        <div style="width:auto;">
                                            <?= Html::img($model->getPhoto(), ['style' => 'height: 20rem;']) ?>                                
                                        </div>
                                        <div class="col text-left">
                                            <h3><?= $model->title ?></h3>
                                            <h5><?= $model->writer ?></h5>
                                            <div>          
                                                <span class="badge badge-dark fa-1x">  
                                                <?php if ($model->rating): ?>
                                                    <?= Yii::$app->formatter->asDecimal($model->rating, 1) ?> 
                                                <?php else: ?>
                                                    нет оценки
                                                <?php endif; ?>
                                                <i class="fas fa-star text-warning"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <?php if ($model->isNewBook()): ?>
                                                    <span class="badge badge-success fa-1x mt-1">Новая</span>
                                                <?php endif; ?>
                                            </div>
                                            <hr />
                                            <p class="mb-2 text-justify"><?= StringHelper::truncateWords($model->description, 80, '...', true) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <?= Html::a('Подробнее', ['/bookshelf/book/view', 'id'=>$model->id], ['class' => 'btn btn-outline-primary mv-link']) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-secondary">Нет данных</div>
                    <?php endif; ?>     
                    <div class="mt-4">
                        <hr />
                        <?= Html::a('<i class="fas fa-angle-right"></i> Все книги', ['/bookshelf/book/index']) ?>              
                    </div>
                </div>                    
            </div>                   
        </div>     
        <div class="col-3">
            <div class="card">
                <div class="card-header">
                    <strong><i class="fas fa-calendar"></i> Календарь литературных дат</strong>
                </div>
                <div class="card-body">
                    <?php if (!empty($modelCalendarToday) && is_array($modelCalendarToday)): ?>
                        <?php foreach ($modelCalendarToday as $model): ?>
                            <div class="alert alert-info">
                                
                                <?php
                                    $dBirthay = Yii::$app->formatter->asDate($model->date_birthday, 'dd.MM');
                                    $dDie = Yii::$app->formatter->asDate($model->date_die, 'dd.MM');
                                    $dNow = Yii::$app->formatter->asDate('today', 'dd.MM');

                                    // if ($dBirthay == $dNow) {
                                    //     echo 'родился';
                                    // }
                                    // elseif ($dDie == $dNow) {
                                    //     echo 'умер';
                                    // }
                                ?>
                                
                                <a href="<?= Url::to(['/bookshelf/calendar/view', 'id'=>$model->id]) ?>" class="mv-link">
                                <b>
                                    <?= $model->writer ?>
                                </b></a>
                                <br />
                                (<?= Yii::$app->formatter->asDate($model->date_birthday) ?>
                                    -
                                <?= Yii::$app->formatter->asDate($model->date_die) ?>)
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-secondary">Нет данных</div>
                    <?php endif; ?>

                    <div class="mt-4">
                        <hr />
                        <?= Html::a('<i class="fas fa-angle-right"></i> Все даты', ['/bookshelf/calendar/index']) ?>
                    </div>
                    
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-header">
                    <strong><i class="fas fa-book-reader"></i> Кто что читает</strong>
                </div>
                <div class="card-body">
                    <?php if (!empty($modelLastWhatReading) && is_array($modelLastWhatReading)): ?>
                        <?php foreach ($modelLastWhatReading as $model): ?>
                            <div class="alert alert-secondary">
                                <strong><?= $model->fio ?></strong><br />
                                <?= $model->title ?> (<?= $model->writer ?>)
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-secondary">Нет данных</div>
                    <?php endif; ?>

                    <div class="mt-4">
                        <hr />
                        <?= Html::a('<i class="fas fa-angle-right"></i> Все записи', ['/bookshelf/what-reading/index']) ?>
                    </div>
                    
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-header">
                    <strong><i class="fas fa-comment-alt"></i> Литературная дискуссия</strong>
                </div>
                <div class="card-body">
                    <?php if (!empty($modelLastDiscussion) && is_array($modelLastDiscussion)): ?>
                        <?php foreach ($modelLastDiscussion as $model): ?>
                            <div class="alert alert-secondary">
                                <?= StringHelper::truncateWords($model->note, 10, '...', true) ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-secondary">Нет данных</div>
                    <?php endif; ?>

                    <div class="mt-4">
                        <hr />
                        <?= Html::a('<i class="fas fa-angle-right"></i> Все дискуссии', ['/bookshelf/discussion/index']) ?>
                    </div>
                    
                </div>
            </div>

        </div>        
    </div>
</div>