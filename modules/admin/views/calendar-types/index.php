<?php
use yii\bootstrap4\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Типы мероприятий';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calendar-index">

    <p class="display-4 border-bottom"><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'type_text',                        
            [
                'attribute' => 'date_create',
                'filter' => false,
                'format' => 'datetime',
            ],
            [
                'attribute' => 'date_update',
                'filter' => false,
                'format' => 'datetime',
            ],
            'author',                      
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Html::a('<i class="fas fa-plus-circle"></i> Добавить', ['/admin/calendar-types/create'], ['class' => 'btn btn-primary']),
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>

</div>
