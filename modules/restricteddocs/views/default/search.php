<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$roleEditor = true;
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,    
    'columns' => [
        'id',

        [
            'class' => 'yii\grid\ActionColumn',
            'header' => $roleEditor ? Html::a('<i class="fas fa-plus-circle"></i> Добавить', ['/restricteddocs/docs/create'], 
                ['class' => 'btn btn-primary mv-link']) : null,
            'template' => '{update} {delete}',            
            'buttons' => [
                'update' => function($url, $model) {
                    return Html::a('<i class="fas fa-pencil-alt"></i>', ['/restricteddocs/docs-orgs/update', 'id'=>$model->id]);
                },
                'delete' => function($url, $model) {
                    return Html::a('<i class="fas fa-trash text-danger"></i>', ['/restricteddocs/docs-orgs/delete', 'id'=>$model->id], [                            
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите удалить?',
                            'method' => 'post',                               
                        ],                        
                    ]);
                },
            ],                         
            'visibleButtons' => [
                'update' => $roleEditor,
                'delete' => $roleEditor,
            ],
        ],
    ],
]) ?>
