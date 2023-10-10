<?php

use app\helpers\DateHelper;
use app\models\AutomationRoutine;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\AutomationRoutineSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Автоматизация рутиных операций';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="automation-routine-index">

    <h1 class="display-4 border-bottom"><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->can('admin')): ?>
    <p>
        <?= Html::a('Добавить ПМ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',            
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function(AutomationRoutine $model) {
                    return $model->title
                        . (DateHelper::dateDiffDays($model->date_create) < 30 ? ' <span class="badge bg-success">Новое</span>' : '');
                },
            ],
            'description',
            'owners',
            'date_create:datetime',
            [
                'label' => 'Действия',
                'format' => 'raw',
                'value' => function(AutomationRoutine $model) {
                    $buttons = Html::a('Подробнее', ['view', 'id'=>$model->id], ['class' => 'btn btn-primary btn-sm mv-link']);         
                    if (Yii::$app->user->can('admin')) {
                        $buttons .= Html::a('Изменить', ['update', 'id'=>$model->id], ['class' => 'btn btn-success btn-sm']);
                        $buttons .= Html::a('Удалить', ['delete', 'id'=>$model->id], [
                            'class' => 'btn btn-danger btn-sm',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить?',
                                'method' => 'post',
                            ],
                        ]);
                    }           
                    return Html::beginTag('div', ['class' => 'btn-group']) 
                            . $buttons
                            . Html::endTag('div');
                },
            ],
        ],
    ]); ?>


</div>
