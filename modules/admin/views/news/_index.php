<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\news\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $modelTree \app\models\Tree */

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'rowOptions' => function (\app\models\news\News $model) {
        if ($model->date_delete != null) {
            return ['class' => 'text-danger bg-danger'];
        }
    },
    'pager' => [
        'firstPageLabel' => 'Первая',
        'lastPageLabel' => 'Последняя',
    ],
    'columns' => [
        [
            'value' => function (\app\models\news\News $model) {
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
            'value' => function  (\app\models\news\News $model) {
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

        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>
