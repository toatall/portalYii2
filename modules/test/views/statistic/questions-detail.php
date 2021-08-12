<?php
/** @var yii\data\ArrayDataProvider $dataProvider */
/** @var integer $idQuestion */
/** @var yii\web\View $this */

use kartik\grid\GridView;

$this->title = 'Статистика по ответам в разрезе ИФНС';
?>

<?= GridView::widget([  
    'id' => 'grid-statistic-questions-detail-' . $idQuestion,  
    'dataProvider' => $dataProvider,
    'columns' => [        
         'org_code:text:Код налогового органа'
        ,'name:text:Наименование налогового органа'
        ,'count_all:integer:Всего ответов'        
        ,'count_right:integer:Правильных ответов',
        [
            'label' => '% правильных ответов',
            'value' => function($item) {            
                if ($item['count_all'] == 0) {
                    return 0 . '%';
                }   
                return round(intval($item['count_right']) / intval($item['count_all']) * 100, 2) . '%';
            },
        ],              
    ],    
    'pjax' => true,    
]) ?>
