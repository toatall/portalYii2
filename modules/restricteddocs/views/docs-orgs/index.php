<?php

use yii\bootstrap5\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

?>
<div class="restricted-docs-orgs-index">
    
    <?php Pjax::begin(['id'=>'pjax-restricted-docs-orgs-index', 'timeout'=>false, 'enablePushState' => false]); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'date_create:datetime',
            'date_update:datetime',
            'author',

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Html::a('<i class="fas fa-plus-circle"></i> Добавить', ['/restricteddocs/docs-orgs/create'], 
                    ['class' => 'btn btn-primary', 'pjax' => true]),
                'template' => '{update} {delete}',   
                'buttons' => [
                    'update' => function($url, $model) {
                        return Html::a('<i class="fas fa-pencil-alt"></i>', ['/restricteddocs/docs-orgs/update', 'id'=>$model->id], ['pjax'=>true]);
                    },
                    'delete' => function($url, $model) {
                        return Html::a('<i class="fas fa-trash text-danger"></i>', ['/restricteddocs/docs-orgs/delete', 'id'=>$model->id], [                            
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
    ]); ?>

    <?php Pjax::end(); ?>

</div>
