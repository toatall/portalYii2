<?php 

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookShelfDiscussion $model */

use yii\bootstrap4\Html;

?>
<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div>
                <h4><i class="far fa-comment-alt"></i> <strong><?= $model->title ?></strong></h4>
            </div>
            <div title="Автор">
                <i class="far fa-user"></i> <?= $model->authorModel->fio ?>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?= $model->note ?>
    </div>
    <div class="card-footer">
        <div class="btn-group">
            <?= Html::a('Подобнее', ['view', 'id'=>$model->id], ['class' => 'btn btn-outline-primary btn-sm']) ?>
        </div>
    </div>
</div>