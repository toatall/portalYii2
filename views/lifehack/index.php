<?php

use app\models\lifehack\Lifehack;
use app\models\lifehack\LifehackFile;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\bootstrap5\Dropdown;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\lifehack\LifehackSearch $searchModel  */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var string|null $tag */


$this->title = 'Лайфхаки' . ($tag != null ? " ({$tag})" : '');
?>
<p class="display-5 border-bottom">
    <?= $this->title ?>
</p>  

<?php Pjax::begin(['id'=>'pjax-lifehack-index', 'timeout'=>false ]) ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [        
        [
            'label' => 'Наименование',
            'value' => function(Lifehack $model) {
                return $model->organizationModel->name . (!empty($model->author_name) ? ' (' . $model->author_name . ')' : '');
            },            
            'format' => 'raw',
            'attribute' => 'searchOrgName',
        ],       
        [
            'attribute' => 'tags',
            'value' => function(Lifehack $model) {
                $res = '';
                foreach ($model->tagsArray as $tag) {
                    $res .= Html::a($tag, ['/lifehack/index', 'tag'=>$tag]) . ' ';
                }
                return $res;
            },
            'format' => 'raw',
        ],        
        'title',
        [
            'label' => 'Оценка',
            'format' => 'raw',
            'value' => function($model) {
                /** @var \app\models\lifehack\Lifehack $model */
                $rate = $model->avg;
                if ($rate) {
                    return '<span class="badge badge-dark fa-1x"><i class="fas fa-star text-warning"></i> ' 
                        . Yii::$app->formatter->asDecimal($rate, 2) . '</span>';
                }
                else {
                    return '';
                }                
            },
        ],
        [
            'label' => 'Файлы',
            'value' => function($model) {
                /** @var LifehackFile[] $files */
                $files = $model->getLifehackFiles()->all();
                $result = '';
                foreach ($files as $file) {
                    $result .= '<i class="far fa-file"></i> ' . Html::a(basename($file->filename), Url::to($file->filename, true), ['target' => '_blank',  'data-pjax'=>0]) . '<br />';
                }
                return $result;
            },
            'format' => 'raw',
        ],        
        'date_create:datetime:Дата',   
        [
            'class' => ActionColumn::class,
            'template' => '<div class="btn-group">{view}{update}{delete}</div>',  
            'buttons' => [
                'view' => function($url, $model) {
                    return Html::a('<i class="fas fa-list-alt"></i>', ['view', 'id'=>$model->id], 
                        ['class'=>'btn btn-outline-primary mv-link', 'title' => 'Подробнее']);
                },
                'update' => function($url, $model) {                    
                    return Html::a('<i class="fas fa-pencil-alt "></i>', ['update', 'id'=>$model->id], 
                        ['class'=>'btn btn-outline-secondary mv-link', 'title' => 'Изменить']);
                },
                'delete' => function($url, $model) {
                    return Html::a('<i class="fas fa-trash"></i>', ['delete', 'id'=>$model->id], 
                        [
                            'class'=>'btn btn-outline-danger mv-link', 
                            'title' => 'Удалить',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить?',
                                'method' => 'post',                           
                            ],
                            'data-pjax' => true,
                        ]);                  
                },
            ],
            'visibleButtons' => [
                'update' => Lifehack::isEditor(),
                'delete' => Lifehack::isEditor(),
            ],
        ],     
    ],
    'toolbar' => [
        '{export}',
        '{toggleData}',        
        [
            'content' => Lifehack::isEditor() ?                
                '<div clas="dropdown dropdown-menu-left">'     
                . Html::a('<i class="fas fa-ellipsis-v"></i>', null, ['data-bs-toggle'=>'dropdown', 'class' => 'btn btn-outline-secondary']) 
                . Dropdown::widget([
                    'items' => [
                        ['label' => '<i class="fas fa-plus-circle"></i> Добавить', 'url' => ['/lifehack/create'], 'linkOptions'=>['class'=>'mv-link']],         
                        ['label' => '<i class="fas fa-tags"></i> Управление тегами', 'url' => ['/lifehack/index-tags'], 'linkOptions'=>['class'=>'mv-link']],                    
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
            'filename' => "Лайфхаки",
        ],
    ],
    'panel' => [
        'type' => GridView::TYPE_DEFAULT,       
    ],
]) ?>

<?php Pjax::end(); ?>
