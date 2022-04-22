<?php

use app\modules\kadry\models\BestProfessional;
use yii\bootstrap4\LinkPager;
use yii\bootstrap4\Html;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\modules\kadry\models\BestProfessional $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Лучший профессионал';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="best-professional-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success mv-link']) ?>
    </p>

    <hr />

    <?php Pjax::begin(['id'=>'pjax-best-professional-index', 'timeout'=>false]) ?>

    <?= $this->render('_search', [
        'model' => $searchModel,
    ]) ?>

    <div class="row">        
    <?php 
    $currentPeriod = null;
    foreach ($dataProvider->getModels() as $model) : ?>
        
        <?php if ($currentPeriod != $model->period_year . $model->period): 
            $currentPeriod = $model->period_year . $model->period;
        ?>
        </div>
        <div class="row mb-2 mt-2 pt-2 border-top">
            <div class="col text-center">    
                <h1 class="text-secondary font-weight-bolder">
                    <i class="fas fa-calendar-alt"></i>
                    <?= BestProfessional::getPeriodNameByCode($model->period) ?> <?= $model->period_year ?>
                </h1>
            </div>
        </div>

        <div class="row">

        <?php endif; ?>


        <div class="col-3 flip-card mb-4">
            <div class="flip-card-inner">
                <div class="flip-card-front rounded">
                    <?php if (($img = $model->getImage()) != null): ?>
                        <?= Html::img($img, [
                            'class' => 'mt-2',
                            'style' => 'width: auto; height:230px;',
                        ]) ?>                        
                    <?php endif; ?>
                    <p class="p-3">
                        <span class="lead"><?= $model->fio ?></span>                          
                        <br />
                        (<?= $model->department ?>)
                        <br />
                        <?= $model->orgCode->name ?>
                    </p>
                </div>
                <div class="flip-card-back rounded">
                    <div class="h-100">
                        <div class="p-3">
                            <?= $model->description ?>                   
                        </div>  
                        <?php if (BestProfessional::isEditor()): ?>
                        <div class="position-absolute w-100 text-center" style="bottom: 2rem;">
                            <div class="btn-group">
                                <?= Html::a('Изменить', ['update', 'id'=>$model->id], ['class' => 'btn btn-primary btn-sm mv-link']) ?>
                                <?= Html::a('Удалить', ['delete', 'id'=>$model->id], [
                                    'class' => 'btn btn-danger btn-sm',
                                    'data' => [
                                        'confirm' => 'Вы уверены, что хотите удалить?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </div>
                        </div>     
                        <?php endif; ?>                 
                    </div>                                       
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>

    <?= LinkPager::widget([
        'pagination' => $dataProvider->pagination,
        'firstPageLabel' => '<span title="Первая страница"><i class="fas fa-chevron-left"></i><i class="fas fa-chevron-left"></i></span>',
        'prevPageLabel' => '<i class="fas fa-chevron-left" title="Предыдущая страница"></i>',
        'lastPageLabel' => '<span title="Последняя страница"><i class="fas fa-chevron-right"></i><i class="fas fa-chevron-right"></i></span>',
        'nextPageLabel' => '<i class="fas fa-chevron-right" title="Следующая страница"></i>',       
    ]) ?>

    <?php Pjax::end()  ?>

</div>
<?php $this->registerCss(<<<CSS

/* The flip card container - set the width and height to whatever you want. We have added the border property to demonstrate that the flip itself goes out of the box on hover (remove perspective if you don't want the 3D effect */
.flip-card {
    background-color: transparent;
    width: 300px;
    height: 400px;
    /* border: 1px solid #f1f1f1; */
    perspective: 1000px; /* Remove this if you don't want the 3D effect */
}

/* This container is needed to position the front and back side */
.flip-card-inner {
    position: relative;
    width: 100%;
    height: 100%;
    text-align: center;
    transition: transform 0.8s;
    transform-style: preserve-3d;
}

/* Do an horizontal flip when you move the mouse over the flip box container */
.flip-card:hover .flip-card-inner {
    transform: rotateY(180deg);
}

/* Position the front and back side */
.flip-card-front, .flip-card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    -webkit-backface-visibility: hidden; /* Safari */
    backface-visibility: hidden;
    overflow: hidden;
}

/* Style the front side (fallback if image is missing) */
.flip-card-front {
    background-color: #6c757d;
    color: white;
}

/* Style the back side */
.flip-card-back {
    background-color: dodgerblue;
    color: white;
    transform: rotateY(180deg);
}

CSS);