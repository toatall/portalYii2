<?php

use app\modules\test\assets\TestAsset;
use yii\helpers\Html;

TestAsset::register($this);

/* @var $this yii\web\View */
/* @var $model \app\modules\test\models\Test */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="test-default-index">
    <?php if (!Yii::$app->request->isAjax): ?>
    <h1><?= $this->title ?></h1>
    <?php endif; ?>

    <div class="list-group-item<?= $model->active ? ' list-group-item-success' : '' ?>">
        <h3 style="font-weight: bold;">
            <p>
                <?= $model->name ?> 
                <span class="title-rating" style="color: goldenrod;"><?= $model->getRatingValue() > 0 ? '<i class="fas fa-star"></i>' . $model->getRatingValue() : null ?></span>
            </p>
            <p style="font-size: medium"><span class="label label-default">Начало <?= Yii::$app->formatter->asDateTime($model->date_start) ?></span></p>
            <p style="font-size: medium"><span class="label label-default">Окончание <?= Yii::$app->formatter->asDateTime($model->date_end) ?></span></p>

        </h3>
        <div class="btn-group">
            <?= Html::a('<i class="fas fa-play"></i> Начать тестирование', ['/test/default/start', 'id'=>$model->id], ['class'=>'btn btn-success mv-link']) ?>
            <?php if ($model->isViewStatistic()): ?>
                <?= Html::a('<i class="fas fa-info"></i> Статистика', ['/test/default/statistic', 'id'=>$model->id], ['class'=>'btn btn-info mv-link']) ?>
            <?php endif; ?>
            <?= Html::a('<i class="fas fa-star"></i> Оценить', ['/test/default/rating', 'id'=>$model->id], ['class' => 'btn btn-default mv-link']) ?>
        </div>
    </div>
</div>