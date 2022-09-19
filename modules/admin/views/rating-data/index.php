<?php

use yii\bootstrap5\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\rating\RatingMain $modelRatingMain */
/** @var app\models\Tree $modelTree */

$this->title = 'Рейтинги';
$this->params['breadcrumbs'][] = ['label' => 'Виды рейтингов для раздела "' . $modelTree->name . '"', 
    'url' => ['/admin/rating/index', 'idTree'=>$modelTree->id]];
$this->params['breadcrumbs'][] = ['label' => $modelRatingMain->name, 
    'url' => ['/admin/rating/view', 'id'=>$modelRatingMain->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rating-data-index">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a('Добавить рейтинг', ['create', 'idMain' => $modelRatingMain->id], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'id' => 'grid-rating-data-index',
        'pjax' => true,
        'responsive' => false,
        // 'striped' => false,
        'dataProvider' => $dataProvider,
        'pager' => [
            'firstPageLabel' => 'Первая',
            'lastPageLabel' => 'Последняя',
        ],
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            //'id',
            //'note',
            'rating_year',
            [
                'attribute' => 'rating_period',
                'value' => function($model) { return $model->periodName; },
            ],
            [
                'label' => 'Файлы',
                'value' => function($model) {                    
                    $res = '';
                    foreach($model->files as $file) {
                        $res .= Html::a('<span class="file">' . basename($file->file_name) . '</span>', 
                            $file->file_name, ['target' => '_blank']) . '<br />';
                    }
                    
                    return $res;
                },
                'format' => 'raw',
            ],
            //'rating_period',
            //'log_change',
            'date_create:datetime',
            //'author',

            [
                'class' => ActionColumn::class,
                'dropdown' => true,
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


</div>

<?php $this->registerJs(<<<JS

    // подставить значки для файлов
    $('.file').each(function() {
        let a = $(this).text().toLowerCase().split('.');
        let icon = 'far fa-file';
        if (a[1] != null) {            
            switch (a[a.length - 1]) {
                case 'pdf':
                    icon = 'far fa-file-pdf';
                    break;
                case 'doc':
                case 'docx':
                    icon = 'far fa-file-word';
                    break;
                case 'xls':
                case 'xlsx':
                    icon = 'far fa-file-excel';
                    break;
                case 'jpg':
                case 'jpeg':
                case 'bmp':
                case 'gif':
                    icon = 'far fa-image';
                    break;
            }            
        }
        $(this).html('<i class="' + icon + '"></i> ' + $(this).html());
    });

JS); ?>
