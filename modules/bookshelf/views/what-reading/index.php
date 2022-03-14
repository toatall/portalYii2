<?php

use app\modules\bookshelf\models\BookShelf;
use yii\bootstrap4\Html;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Кто что читает';
$this->params['breadcrumbs'][] = ['label' => 'Книжная полка', 'url' => ['/bookshelf/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="what-reading-index">

    <p class="display-4 border-bottom">
        <?= Html::encode($this->title) ?>
    </p>

    <?php if (BookShelf::isEditor()): ?>
    <div class="btn-group mt-2 mb-2">
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-outline-success btn-sm mv-link']) ?>        
    </div>
    <?php endif; ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => SerialColumn::class],
            'fio',
            'writer',
            'title',
            'authorModel.fio',
            'date_create:datetime',      
            [
                'value' => function($model) {
                    /** @var app\modules\bookshelf\models\WhatReading $model */
                    $html = '';
                    if (BookShelf::isEditor()) {
                        $html = Html::beginTag('div', ['class' => 'btn-group']);
                        $html .= Html::a('<i class="fas fa-pencil-alt"></i>', ['update', 'id'=>$model->id], [
                            'class' => 'mv-link btn btn-outline-primary btn-sm', 
                            'pjax' => false, 
                            'title' => 'Редактировать',
                        ]);
                        $html .= Html::a('<i class="fas fa-trash-alt"></i>', ['delete', 'id'=>$model->id], [
                            'title' => 'Удалить',
                            'class' => 'btn btn-outline-danger btn-sm',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить?',
                                'method' => 'post',
                                'pjax' => true,
                            ],
                        ]);
                        $html .= Html::endTag('div');
                    }
                    return $html;
                },
                'format' => 'raw',
            ],
        ],
    ]); ?>


</div>
