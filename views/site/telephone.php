<?php
/** @var app\controllers\SiteController $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\bootstrap5\Html;
use kartik\grid\GridView;

$this->title = 'Телефонные справочники';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-telephone">
    
    <div class="col border-bottom mb-2">
        <p class="display-5">
            <?= $this->title ?>
        </p>    
    </div>   
    <div class="mb-2">
        <?= Html::a('Телефонный справочник (из СЭД-Регион) <i class="fas fa-info-circle"></i>', 
            ['/telephone/index'], ['class' => 'font-weight-bold', 'data-toggle'=>'popover', 'data-content'=>'В тестовом режиме']) ?>
    </div>
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
    ]); ?>

</div>
