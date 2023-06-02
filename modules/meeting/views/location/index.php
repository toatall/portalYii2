<?php
/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */

use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Кабинеты';
?>

<h2 class="title mv-hide"><?= $this->title ?></h2>

<?php Pjax::begin(['id' => 'meeting-location-index', 'timeout' => false, 'enablePushState' => false]) ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [    
        'id',
        'location',
        'date_create:datetime',
        [
            'header' => Html::a('<i class="fas fa-plus"></i>', ['create'], ['class' => 'btn btn-success btn-sm', 'data-pjax' => true]),
            'class' => ActionColumn::class,
            'template' => '{update} {delete}',
            'buttonOptions' => [
                'data-pjax' => true,
            ],
            'buttons' => [
                
            ],
        ],
    ],
    'pager' => ['class' => LinkPager::class],
]) ?>

<?php Pjax::end() ?>

<?php 
$url = Url::to(['index']);
$this->registerJs(<<<JS
    
    (function(){
        const el = $('.meeting-location-form').parents('[data-pjax-container]')
        $(document).on('pjax:success', el, function(event, data) {
            if (data.toString().replace(/\"/g, '').toUpperCase() == 'OK') {
                $.pjax({ url: '$url', container: '#meeting-location-index', push: false, timeout: false, scrollTo: false })            
            }
        })
    }())    

JS); ?>