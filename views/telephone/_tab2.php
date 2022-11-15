<?php
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\bootstrap5\Html;
use kartik\grid\GridView;

$this->title = 'Телефонные справочники';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-telephone mt-2">
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'showHeader' => false,
        'summary' => false,
        'columns' => [
            [
                'value' => function($model) {
                    return Html::a('<i class="fas fa-file-word"></i> Скачать', $model->telephone_file, ['target'=>'_blank', 'class'=>'btn btn-primary']);
                },
                'format' => 'raw',
            ],
            'id_organization',
            'organization.name',
            'dop_text',
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
