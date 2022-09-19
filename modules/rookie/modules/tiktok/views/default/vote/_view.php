<?php

/** @var \yii\web\View $this */
/** @var \app\modules\rookie\modules\tiktok\models\Tiktok $model */

?>

<div class="row">
    <div class="col text-center">
        <span class="badge bg-success fa-1x">
            <?= $model->getAttributeLabel('rate_1') ?>:
            <span class="fas fa-star text-warning"></span> <?= $model->avgRate1 ?>
        </span>
    </div>  
    <div class="col text-center">
        <span class="badge bg-success fa-1x">
            <?= $model->getAttributeLabel('rate_2') ?>:
            <span class="fas fa-star text-warning"></span> <?= $model->avgRate2 ?>
        </span>
    </div>   
    <div class="col text-center">
        <span class="badge bg-success fa-1x">
            <?= $model->getAttributeLabel('rate_3') ?>:
            <span class="fas fa-star text-warning"></span> <?= $model->avgRate3 ?>
        </span>
    </div>  
    
    <div class="col text-center ml-3">
        <span class="badge badge-secondary fa-1x">
            Количество голосов:
            <?= $model->countVotes ?>
        </span>
    </div>  
</div>