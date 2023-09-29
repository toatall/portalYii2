<?php

/** @var \yii\web\View $this */

use app\assets\FancyappsUIAsset;
use app\modules\like\widgets\LikeWidget;
use yii\bootstrap5\Html;

FancyappsUIAsset::register($this);

/** @var \app\modules\contest\modules\pets\models\Pets[] $models */

$index = 0;
?>

<div class="mb-2">
    <?php if (Yii::$app->user->can('admin')): ?>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    <?php endif; ?>
</div>

<div class="row">
    <?php foreach($models as $model): ?>
        <div class="col-3">
            <div class="card shadow" style="background-color: rgba(0, 200, 0, .07);">
                <div class="card-header">
                    <b><?= $model->pet_name ?></b><br />
                    <?= $model->pet_age ?>
                </div>
                <?php if ($firstImg = $model->getFiles()): ?>
                <img src="<?= $firstImg[random_int(0, count($firstImg)-1)] ?>" />
                <?php endif; ?>
                <div class="card-body text-center">
                    <?= $model->owner->fio ?? $model->pet_owner ?>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <?= Html::a('Подробнее', ['view', 'id' => $model->id], ['class' => 'btn btn-success mv-link']) ?>
                    <?php if (Yii::$app->user->can('admin')): ?>
                        <?= Html::a('<i class="fas fa-pencil"></i>', ['update', 'id' => $model->id], ['class' => 'btn btn-success', 'title' => 'Редактировать']) ?>
                    <?php endif; ?>
                    <?= LikeWidget::widget([
                        'unique' => 'contest-pets-' . $model->id,
                        'showLikers' => true,
                        'disabled' => true,
                        'btnLikeText' => '',
                        'btnUnlikeText' => '',
                        'btnLikeIcon' => '',
                        'btnUnlikeIcon' => '',
                        'showZero' => true,
                    ]) ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php $this->registerJs(<<<JS
        
JS); ?>