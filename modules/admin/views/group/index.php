<?php

use yii\bootstrap4\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use kartik\select2\Select2;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\Group $searchModel */

$this->title = 'Группы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-index">

    <h1 class="display-4 border-bottom"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<i class="fas fa-plus-circle"></i> Добавить группу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(['id' => 'pjax-group-index', 'timeout' => false, 'enablePushState' => false]) ?>
    <?= GridView::widget([
        'filterModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',            
            [
                'label' => 'Принадлежность',
                'filter' => false,
                'value' => function($model) {
                    if ($model->is_global) {
                        return Html::tag('span', 'Глобальная', ['class' => 'badge badge-success fa-1x']); 
                    }
                    else {
                        return Html::tag('span', $model->id_organization, ['class' => 'badge badge-primary fa-1x']);
                    }
                },
                'format' => 'raw',
            ],
            /*[
                'attribute' => 'id_organization',
                'filter' => Select2::widget([
                    'model'=>$searchModel,
                    'attribute'=>'id_organization',
                    'data'=>[
                        '' => 'Все',
                        '0000' => 'Глобальная группа',
                    ],
                ])
            ],*/
            [
                'attribute' => 'is_global',
                'format' => 'boolean',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'is_global',
                    'data' => [
                        '1' => 'Да',
                        '0' => 'Нет',
                    ],
                    'options' => [
                        'placeholder' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
            ],
            'name',
            'description',           
            [
                'value' => function($model) {
                    return Html::a('<!--i class="fas fa-users fa-2x"></i-->Управление', 
                        ['manage', 'id'=>$model->id], ['class'=>'btn btn-outline-success']);
                },
                'format' => 'raw',
            ],
            [
                'class' => ActionColumn::class,              
            ],
        ],
    ]); ?>
    <?php Pjax::end() ?>


</div>
