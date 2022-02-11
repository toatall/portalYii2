<?php

/** @var \yii\web\View $this */
/** @var app\modules\kadry\models\education\Education[] $models */

use yii\bootstrap4\Html;
$this->title = 'Лекториум';
?>

<?php if (Yii::$app->user->can('admin')): ?>
    <div class="row col mb-4">
        <?= Html::a('<i class="fas fa-"></i> Добавить образовательную программу', ['/kadry/education-admin/create'], ['class' => 'btn btn-sm btn-outline-success']) ?>
    </div>    
<?php endif; ?>

<div class="row">
<?php foreach ($models as $item): ?>
    <div class="col-md-4">
        <div class="card mb-4 shadow-sm">
            <img src="<?= $item->getThumbnailImage() ?>" />
            <hr class="m-0" />           
            <div class="card-header">
                <h5 class="font-weight-bolder"><?= $item->title ?? null ?></h5>
            </div>
            <div class="card-body">                
                <p class="card-text text-justify"><?= $item->description ?? null ?></p>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                        <?= Html::a('Просмотр', ['/kadry/education/view', 'id'=>$item->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                    </div>
                    <?php if (isset($item->duration)): ?>
                    <small class="text-muted"><i class="fas fa-clock"></i> <?= $item->duration ?></small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>