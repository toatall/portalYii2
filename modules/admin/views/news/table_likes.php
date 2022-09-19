<?php

use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

?>

<?= GridView::widget([
    'id' => 'grid-news-table-likes',
    'pjax' => true,
    'striped' => false,
    'dataProvider' => $dataProvider,
    'rowOptions' => function ($model, $key, $index, $grid) {
        if ($model['user_disabled_ad'] == 1) {
            return ['class' => 'text-danger'];
        }        
    },
    'columns' => [
        'fio:text:ФИО',
        [
            'label' => 'Учетная запись',
            'format' => 'raw',
            'value' => function($model) {
                return $model['username'] . ' ' . (
                    $model['user_disabled_ad'] == 1 
                        ? '<br /><span class="badge bg-danger">Учетная запись отключена</span>'
                        : ''
                );
            },
        ],
        'current_organization:text:Код НО',
        'organization_name:text:Наименование НО',
        'department:text:Отдел',        
        'ip_address:text:IP адрес',
        'date_create:datetime:Дата',        
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