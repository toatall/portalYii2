<?php

use kartik\grid\GridView;
use yii\bootstrap5\Html;
use kartik\grid\ActionColumn;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

?>
<?php Pjax::begin(['id'=>'pjax-lifehack-index-tags', 'timeout'=>false, 'enablePushState'=>false]) ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id', 
        'tag',        
        'date_create:datetime',        
        [
            'class' => ActionColumn::class,
            'header' => Html::a('<i class="fas fa-plus-circle"></i> Добавить', ['/lifehack/create-tag'], 
                ['class' => 'btn btn-primary', 'pjax' => true]),
                'template' => '{update} {delete}',   
            'buttons' => [
                'update' => function($url, $model) {
                    return Html::a('<i class="fas fa-pencil-alt"></i>', ['lifehack/update-tag', 'id'=>$model->id], ['pjax'=>true]);
                },
                'delete' => function($url, $model) {
                    return Html::a('<i class="fas fa-trash text-danger"></i>', ['lifehack/delete-tag', 'id'=>$model->id], [                            
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите удалить?',
                            'method' => 'post',                               
                        ],
                        'data-pjax' => true,
                    ]);
                },
            ],   
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
]) ?>

<?php Pjax::end() ?>