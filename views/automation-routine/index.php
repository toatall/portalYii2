<?php

use app\helpers\DateHelper;
use app\models\AutomationRoutine;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\AutomationRoutineSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Автоматизация рутиных операций';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="automation-routine-index">

    <h1 class="display-4 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <div class="alert alert-info">
        <p style="text-align: justify;">      
            <h6>Уважаемые коллеги!</h6>
            Приведенные программные модули (ПМ) распространяются в рамках распоряжения ФНС России №226 от 07.07.2023 по реализации пилотного проекта «Автоматизация рутинных операций в 
            технологических процессах ФНС России с применением роботизации (1-я очередь)».<br />
            <u>Примите участие в тестировании ПМ, оцените его пользу. Используйте в работе!</u>
        </p>
    </div>

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
            [
                'label' => 'Оценка',
                'format' => 'raw',
                'value' => function(AutomationRoutine $model) {                    
                    $rate = $model->getRate();
                    if ($rate) {
                        return Html::tag('span', 
                            '<i class="fas fa-star text-warning"></i> ' . $rate, 
                            ['class' => 'badge bg-light border text-dark fs-6 me-1']);
                    }
                    else {
                        return Html::tag('span', 
                            'нет оценки', 
                            ['class' => 'badge bg-light border fw-normal text-dark fs-6 me-1']);
                    }                    
                },
            ],
            'description',
            'owners',
            'date_create:datetime',
            [
                'label' => 'Действия',
                'format' => 'raw',
                'value' => function(AutomationRoutine $model) {
                    $buttons = Html::a('Подробнее', ['view', 'id'=>$model->id], ['class' => 'btn btn-primary btn-sm']);         
                    if (Yii::$app->user->can('admin')) {
                        $buttons .= Html::a('<i class="fas fa-pencil"></i>', ['update', 'id'=>$model->id], ['class' => 'btn btn-success btn-sm']);
                        $buttons .= Html::a('<i class="fas fa-trash-alt"></i>', ['delete', 'id'=>$model->id], [
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
