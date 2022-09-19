<?php

use app\modules\bookshelf\models\BookShelf;
use yii\bootstrap5\Html;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Кто что читает';
$this->params['breadcrumbs'][] = ['label' => 'Книжная полка', 'url' => ['/bookshelf/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="what-reading-index card card-body bg-dark animate__animated animate__fadeInUp">

    <p class="display-5 text-white font-weight-bolder">
        <?= Html::a('Книжная полка', ['/bookshelf'], ['class' => 'text-white']) ?>
        &rsaquo;
        <span class="font-weight-normal text-secondary"><?= Html::encode($this->title) ?></span>
    </p>
    <hr class="border-white" />

    <?php if (BookShelf::isEditor()): ?>
        <div>
            <div class="btn-group mt-2 mb-4">
                <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success mv-link']) ?>       
            </div>
        </div>
    <?php endif; ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => [
            'class' => 'table-secondary',
        ],
        'summaryOptions' => [
            'class' => 'text-white',
        ],
        'columns' => [
            ['class' => SerialColumn::class],
            [
                'attribute' => 'fio',
                'format' => 'raw',
                'value' => function($model) {
                    /** @var app\modules\bookshelf\models\WhatReading $model */
                    $img = $model->getImage();
                    $imgHtml = $img ? Html::img($img, ['style' => 'width: 7rem', 'class' => 'img-thumbnail']) . '<br />' : null;
                    return $imgHtml . $model->fio;
                },
                'contentOptions' => ['class' => 'text-center'],
            ],
            // 'fio',
            'writer',
            'title',
            // 'authorModel.fio',
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
