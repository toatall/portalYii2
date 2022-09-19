<?php
/** @var yii\web\View $this */
/** @var app\models\rating\RatingData[] $model */

use yii\bootstrap5\Html;
?>

<ul class="row">
    <?php foreach ($model as $m): ?>
        <div class="col-3">
            <div class="card mt-2">
                <div class="card-header">
                    <strong><?= $m->getPeriodName() ?></strong>
                </div>
                <div class="card-body">
                    <?php foreach ($m->files as $file): ?>                       
                    <?= Html::a('<i class="far fa-file"></i> ' . basename($file->file_name), ['/file/download', 'id'=>$file->id], ['target'=>'_blank']) ?><br />              
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
    <?php endforeach; ?>
</ul>
