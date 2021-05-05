<?php
/* @var $this \yii\web\View */
/* @var $model \app\models\rating\RatingData[] */

use yii\helpers\Html;
?>

<ul class="row">
    <?php foreach ($model as $m): ?>
        <div class="col-sm-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong><?= $m->getPeriodName() ?></strong>
                </div>
                <div class="panel-body">
                    <?php foreach ($m->files as $file): ?>
                    <?= Html::a(basename($file->file_name), ['/file/download', 'id'=>$file->id], ['target'=>'_blank']) ?>
                        <?php //(<i class="fas fa-download"></i> <?= $file->count_download ?>
                    <br />
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!--li class="col-sm-3 col-md-2 thumbnail text-center" style="margin-right: 15px;">
            <div class="page-header">
                <strong><?= $m->getPeriodName() ?></strong>
            </div>
            <div class="thumb-rating">
                <?= 'files' ?>
            </div>
        </li-->
    <?php endforeach; ?>
</ul>
