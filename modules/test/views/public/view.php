<?php

use app\modules\test\assets\TestAsset;
use app\modules\test\models\Test;
use yii\helpers\Html;

TestAsset::register($this);

/** @var yii\web\View $this */
/** @var \app\modules\test\models\Test $model  */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="test-public-index">   
    <div class="list-group-item<?= $model->active ? ' list-group-item-success' : '' ?>">
        <h3 style="font-weight: bold;">
            <p>
                <?= $model->name ?> 
                <span class="title-rating" style="color: goldenrod;"><?= $model->getRatingValue() > 0 ? '<i class="fas fa-star"></i>' . $model->getRatingValue() : null ?></span>
            </p>
            <p style="font-size: medium"><span class="">Начало <?= Yii::$app->formatter->asDateTime($model->date_start) ?></span></p>
            <p style="font-size: medium"><span class="font-">Окончание <?= Yii::$app->formatter->asDateTime($model->date_end) ?></span></p>

        </h3>
        <div class="btn-group" data-group="main">
            <?php if ($model->processStatus() === Test::PROCESS_STATUS_RUNNING): ?>
            <?= Html::a('<i class="fas fa-play"></i> Начать тестирование', ['/test/public/start', 'id'=>$model->id], 
                ['class' => 'btn btn-success', 'target'=>'_blank', 'data' => [
                'confirm' => 'Вы уверены, что хотите начать тестирование?',
            ]]) ?>
            <?php endif; ?>
            <?php if ($model->canStatisticTest()): ?>
                <div class="btn-group">
                    <button class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-info"></i> Статистика <span class="caret" style="vertical-align: middle; border-top: 4px solid white;"></span>
                    </button>
                    <ul class="dropdown-menu">                        
                        <li><?= Html::a('Общая', ['/test/statistic/general', 'id'=>$model->id], ['target'=>'_blank']) ?></li>
                        <li><?= Html::a('По сотрудникам', ['/test/statistic/users', 'id'=>$model->id], ['target'=>'_blank']) ?></li>
                        <li><?= Html::a('По вопросам', ['/test/statistic/questions', 'id'=>$model->id], ['target'=>'_blank']) ?></li>
                        <li><?= Html::a('Оценки', ['/test/statistic/opinion', 'id'=>$model->id], ['target'=>'_blank']) ?></li>
                    </ul>
                </div>                
            <?php endif; ?>
            <?= Html::a('<i class="fas fa-star"></i> Оценить', ['/test/public/rating', 'id'=>$model->id], ['class'=>'btn btn-default', 'target'=>'_blank']) ?>
        </div>
    </div>
</div>