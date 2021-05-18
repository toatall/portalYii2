<?php

use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $models array */


$this->title = 'Неделя добрых дел';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="row">
    
    <?php foreach ($models as $model): ?>
    
    <div class="col-sm-4">
        <div class="panel panel-default text-center">
            <div class="panel-heading panel-heading-150">
                <h4 class="text-muted" style="font-weight: 800;">
                    <div class="valign-center">
                        <?= $model['title'] ?>
                        <br /><small><?= $model['nomination'] ?></small>
                    </div>
                </h4>
            </div>
            <div class="panel-body height-300">
                <img src="<?= $model['thumbnailImage'] ?>" class="thumbnail img-thumb-preview" />
            </div>
            <div class="panel-footer">
                <a href="<?= Url::to(['/news/view', 'id'=>$model['idNews']]) ?>" class="btn btn-primary middle mv-link" style="font-weight: 800;">Просмотр</a>
            </div>
        </div>
    </div>
    
    <?php endforeach; ?>        
        
</div>




<?php 
$this->registerCss(<<<CSS
    .img-thumb-preview {
        display: block; 
        position: relative;
        margin: 0 auto;
        max-width: 100%;
        max-height: 300px;
    }
    .height-300 {
        height: 330px;
    }
    .col-sm-4 {
        margin-bottom: 20px;
    }
        
    .panel-heading-150 {    
        height: 150px;
    }
   
CSS
); 
 ?>        