<?php

use app\modules\kadry\models\BestProfessional;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\kadry\models\BestProfessional $model */

$this->title = $model->fio;
$this->params['breadcrumbs'][] = ['label' => 'Лучший профессионал', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::a('<i class="fas fa-arrow-circle-left"></i>', ['index'], [
    'style' => 'position: fixed; left:2rem; top: 45%; font-size: 4rem;',   
    'class' => 'text-secondary',
    'title' => 'Назад',
]) ?>

<div class="best-professional-view">

    <?php if (BestProfessional::isEditor()): ?>
        <div class="" style="bottom: 2rem;">
            <div class="btn-group">
                <?= Html::a('Изменить', ['update', 'id'=>$model->id], ['class' => 'btn btn-outline-primary mv-link']) ?>
                <?= Html::a('Удалить', ['delete', 'id'=>$model->id], [
                    'class' => 'btn btn-outline-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>     
    <?php endif; ?>

    <div class="row">
        <div class="col-7">
            <?php if (($img = $model->getImage()) != null): ?>
                <?= Html::img($img, [
                    'class' => 'mt-2 img-thumbnail',                 
                ]) ?>                        
            <?php endif; ?>
        </div>
        <div class="col-5">
            <h1 class="text-center" style="text-shadow: 2px 2px #aaa;"><?= $model->nomination ?></h1>
            <hr />
            <h3 class="text-justify">
                <?= $model->description ?>
            </h3>
            <hr />
            <div class="mt-3 font-weight-bolder">                                                    
                <?= $model->orgCode->name ?>
            </div>
            <div class="mt-3 font-weight-bolder">                                                    
                <?= $model->department ?>
            </div>
        </div>
    </div>    

</div>
