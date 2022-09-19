<?php

use app\models\news\News;
use kartik\editable\Editable;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use kartik\grid\EditableColumn;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\news\NewsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\Tree $modelTree */

?>

<?= GridView::widget([  
    'id' => 'grid-news-index',
    'pjax' => true,
    'responsive' => false,
    'striped' => false,
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'rowOptions' => function (News $model) {
        if ($model->date_delete != null) {
            return ['class' => 'text-danger'];
        }
    },
    'pager' => [
        'firstPageLabel' => 'Первая',
        'lastPageLabel' => 'Последняя',
    ],
    'columns' => [
        [
            'value' => function (News $model) {
                $result = '';

                // опубликовано / не опубликовано
                if ($model->flag_enable) {
                    $result .= '<i class="fas fa-check text-success" title="Опубликовано"></i> ';
                }
                else {
                    $result .= '<i class="fas fa-check text-danger" title="Не опубликовано"></i> ';
                }

                // закрепление новости
                if ($model->date_top != null) {
                    $result .= '<i class="fas fa-clock" title="Новость закреплена до ' . $model->date_top . '"></i>  ';
                }

                // удалена новость
                if ($model->date_delete != null) {
                    $result = '<i class="fas fa-trash text-danger" title="Новость удалена"></i>';
                }

                return $result;
            },
            'format' => 'raw',
        ],
        'id',
        [
            'attribute' => 'title',
            'value' => function  (News $model) {
                return Html::tag('span', $model->getTitleShort(), ['title' => $model->title]);
            },
            'format' => 'raw',
        ],
        //'date_sort:datetime',
        'date_create:datetime',
        //'message2',
        //'author',
        //'general_page',
        'date_start_pub:date',
        'date_end_pub:date',
        [
            'class' => EditableColumn::class,
            'attribute' => 'flag_enable',
            'editableOptions' => [                                   
                'inputType' => Editable::INPUT_CHECKBOX,
                'formOptions' => ['action' => ['/admin/news/editnews']],
                'options' => [
                    'template' => '<div class="form-check form-switch" style="font-size: 1.2rem;">{input} {label}</div><div>{error}</div>',
                    'class' => 'form-check-input',
                ],
            ],
            'format' => 'boolean',
            'refreshGrid' => true,
        ],
        //'flag_enable',
        //'thumbail_title',
        //'thumbail_image',
        //'thumbail_text',
        //'date_create',
        //'date_edit',
        //'log_change',
        //'on_general_page',
        //'count_like',
        //'count_comment',
        //'count_visit',
        //'tags',
        //'date_sort',
        [
            'class' => ActionColumn::class,
            'dropdown' => true,
            'template' => '{view} {update} {delete} <hr class="dropdown-divider" /> {history} {likes}',        
            'buttons' => [
                'history' => function($url, $model) {
                    return Html::a('<i class="far fa-chart-bar"></i> Просмотры', ['/admin/news/history', 'id'=>$model->id], [
                        'class' => 'dropdown-item mv-link', 
                        'data-pjax' => '0',
                    ]);
                },
                'likes' => function($url, $model) {
                    return Html::a('<i class="fas fa-heart"></i> Лайки', ['/admin/news/likes', 'id'=>$model->id], [
                        'class' => 'dropdown-item mv-link', 
                        'data-pjax' => '0',
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
]); ?>
