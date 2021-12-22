<?php

use app\models\lifehack\Lifehack;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\bootstrap4\Dropdown;
use yii\bootstrap4\Html;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\lifehack\LifehackSearch $searchModel  */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var string|null $tag */


$this->title = 'Лайфхаки' . ($tag != null ? " ({$tag})" : '');
?>
<p class="display-4">
    <?= $this->title ?>
</p>  

<?php Pjax::begin(['id'=>'pjax-lifehack-index', 'timeout'=>false ]) ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [ 
        'organizationModel.name',
        'tags',
        'title',
        'files',
        'date_create',   
        [
            'class' => ActionColumn::class,
            'template' => '{view}',  
            'buttons' => [
                'view' => function($url, $model) {
                    return Html::a('<i class="fas fa-eye"></i> Просмотр', ['view', 'id'=>$model->id], ['class'=>'btn btn-primary btn-sm mv-link']);
                },
            ],
        ],     
    ],
    'toolbar' => [
        '{export}',
        '{toggleData}',        
        [
            'content' => Lifehack::isEditor() ?                
                '<div clas="dropdown dropdown-menu-left">'     
                . Html::a('<i class="fas fa-ellipsis-v"></i>', null, ['data-toggle'=>'dropdown', 'class' => 'btn']) 
                . Dropdown::widget([
                    'items' => [
                        ['label' => '<i class="fas fa-plus-circle"></i> Добавить', 'url' => ['/lifehack/create'], 'linkOptions'=>['class'=>'mv-link']],         
                        ['label' => 'Управление тегами', 'url' => ['/lifehack/index-tags'], 'linkOptions'=>['class'=>'mv-link']],                    
                    ],
                    'options' => [
                        'class' => 'dropdown-menu-right',
                    ],
                    'encodeLabels' => false,
                ])
                . '</div>' : '',
        ],
    ],    
    'exportConfig' => [        
        GridView::EXCEL => [
            'filename' => "Награды и поощрения {$searchModel->org_code}",  
        ],      
    ],    
    'panel' => [
        'type' => GridView::TYPE_DEFAULT,       
    ],
]) ?>

<?php Pjax::end(); ?>
