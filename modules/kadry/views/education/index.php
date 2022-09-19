<?php

/** @var \yii\web\View $this */
/** @var app\modules\kadry\models\education\Education[] $models */

use yii\bootstrap5\Html;
$this->title = 'Комплекс программ профессионального развития в рамках цифровой трансформации';
?>

<?php if (Yii::$app->user->can('admin')): ?>
    <div class="mb-4">
        <?= Html::a('<i class="fas fa-"></i> Добавить образовательную программу', ['/kadry/education-admin/create'], ['class' => 'btn btn-sm btn-outline-success']) ?>
    </div>    
<?php endif; ?>

<div class="row">
<?php foreach ($models as $item): ?>
    <div class="col-6 mb-4">
        <div class="card mb-4 shadow-sm h-100">            
            <img src="<?= $item->getThumbnailImage() ?>" class="card-img" />            
            <hr class="m-0" />           
            <div class="card-header">
                <h5 class="font-weight-bolder"><?= $item->title ?? null ?></h5>
            </div>
            <div class="bg-success" style="height:0.2rem; width: <?= $item->educationUser->percent ?? 0 ?>%;"></div>
            <div class="card-body d-flex align-items-start flex-column">
                <div class="mb-auto mw-100">
                    <p class="card-text text-justify"><?= $item->description ?? null ?></p>
                </div>
                <div class="w-100">
                    <div class="btn-group float-left">
                        <?= Html::a('Просмотр', ['/kadry/education/view', 'id'=>$item->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                    </div>
                    <?php if (!empty($item->duration)): ?>
                    <div class="float-right">
                        <small class="text-muted"><i class="fas fa-clock"></i> <?= $item->duration ?></small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>