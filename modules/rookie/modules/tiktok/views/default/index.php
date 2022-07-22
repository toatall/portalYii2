<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Tiktok баттл';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tiktok-index">
    
    <?php if (Yii::$app->user->can('admin')): ?>
        <?= Html::a("Add tiktok's video", ['create'], ['class' => 'btn btn-outline-success mv-link mb-3']) ?>
    <?php endif; ?>
        
    <div class="row">
    <?php foreach($dataProvider->getModels() as $model): 
        /** @var app\modules\rookie\modules\tiktok\models\Tiktok $model */
        ?>
        <div class="col-4 mb-3">
            <a href="<?= Url::to(['/rookie/tiktok/default/view', 'id'=>$model->id]) ?>" class="mv-link link-no-hover">
                <div class="card border-0" style="background-color: black;">
                    <img src="/public/content/rookie/tiktok/img/card-img.png" class="card-img" />
                    <!-- <video src="<?= $model->filename ?>#t=1.1" class="card-img" /> -->
                    <!-- <img src="<?= $model->filename ?>#t=1.1" class="card-img" /> -->
                    <div class="card-img-overlay" style="background-color: rgba(200, 200, 200, .7);">
                        <div class="card-body text-center h-100">
                            <p class="text-dark fa-2x font-weight-bolder" style="text-shadow: 1px 1px white;">
                                <?= $model->departmentModel->department_name ?>
                            </p>
                            <p class="text-white" style="text-shadow: 1px 1px black;">
                                <?= $model->description ?>
                            </p>                           
                        </div>                        
                    </div>                    
                    <div class="card-footer border-top border-secondary">

                        <?php if (Yii::$app->user->can('admin')): ?>
                        <div class="btn-group">
                            <?= Html::a('<i class="fas fa-pencil-alt"></i>', ['/rookie/tiktok/default/update', 'id'=>$model->id], 
                                ['class' => 'btn btn-outline-primary btn-sm mv-link']) ?>
                            <?= Html::a('<i class="far fa-trash-alt"></i>', ['/rookie/tiktok/default/delete', 'id'=>$model->id], 
                                ['class' => 'btn btn-outline-danger btn-sm',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ]]) ?>
                        </div>
                        <?php endif; ?>

                        <div class="float-right">
                            <i class="fas fa-star text-warning pt-2" style="font-size: 1.1rem;"></i>                            
                            <div class="btn-group">
                                <span class="btn btn-outline-secondary text-white" data-toggle="tooltip" title="креативность"><?= $model->avgRate1 ?></span>
                                <span class="btn btn-outline-secondary text-white" data-toggle="tooltip" title="творчество"><?= $model->avgRate2 ?></span>
                                <span class="btn btn-outline-secondary text-white" data-toggle="tooltip" title="качество видеоролика"><?= $model->avgRate3 ?></span>
                                <span class="btn btn-outline-secondary text-white ml-2" data-toggle="tooltip" title="количество голосов"><?= $model->countVotes ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
    </div>

</div>
<?php 
$this->registerJs(<<<JS
    $(modalViewer).off('onRequestJsonAfterAutoCloseModal');
    $(modalViewer).on('onRequestJsonAfterAutoCloseModal', function(event, data) {    
        document.location.reload();
    });
    $('[data-toggle="tooltip"]').tooltip();
JS); 
$this->registerCss(<<<CSS
    .link-no-hover, .link-no-hover:hover {
        text-decoration: none;
    }
CSS);
?>