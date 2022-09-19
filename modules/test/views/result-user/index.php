<?php

use app\modules\test\models\TestResult;
use kartik\grid\ExpandRowColumn;
use yii\bootstrap5\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Результаты тестов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-question-index">
    <div class="card shadow mb-4">
        <div class="card-header font-weight-bolder">
            <h1><?= Html::encode($this->title) ?></h1>  
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [ 
                    'id',                   
                    [
                        'attribute' => 'test.name',
                        'group' => true,
                    ],
                    [
                        'label' => 'Статус',
                        'value' => function($model) {
                            /** @var app\modules\test\models\TestResult $model */
                            if ($model->status === TestResult::STATUS_FINISH) {
                                return '<i class="fas fa-check-circle text-success" title="Завершено"></i>';
                            }
                            if ($model->status === TestResult::STATUS_CANCEL) {
                                return '<i class="fas fa-times text-danger" title="Отменено"></i>';
                            }
                            if ($model->status === TestResult::STATUS_START) {
                                return '<i class="fas fa-running text-primary" title="Выполняется"></i>';
                            }
                            return '<i class="fas fa-question" title="Статус не известен"></i>';
                        },
                        'format' => 'raw',
                    ],                    
                    'countQuestions:text:Количество вопросов',
                    'countRightQuestions:text:Количество вопросов в правильным ответом',
                    [
                        'label' => '% вопросов в правильным ответом ',
                        'value' => function($model) {
                            /** @var app\modules\test\models\TestResult $model */
                            if ($model->getCountQuestions() > 0) {
                                return round($model->getCountRightQuestions() / $model->getCountQuestions() * 100, 2) . '%';
                            }
                            return 0 . '%';
                        },
                    ],
                    'date_create:datetime:Дата',
                    'duration:text:Продолжительность',
                    [
                        'class' => ExpandRowColumn::class,
                        'value' => function() { return GridView::ROW_COLLAPSED; },
                        'detailUrl' => Url::to(['/test/result/view-ajax']),
                    ],
                ],
                'toolbar' => [
                    '{export}',
                    '{toggleData}',
                ],
                'export' => [
                    'showConfirmAlert' => false,
                ],
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT,       
                ],
            ]); ?>
        </div>
    </div>
</div>
