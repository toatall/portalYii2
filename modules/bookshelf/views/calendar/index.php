<?php

use app\modules\bookshelf\models\BookShelf;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookCalendarSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Календарь литературных дат';
$this->params['breadcrumbs'][] = ['label' => 'Книжная полка', 'url' => ['/bookshelf/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-shelf-calendar-index card card-body bg-dark animate__animated animate__fadeInUp">

    <p class="display-4 text-white font-weight-bolder">
        <?= Html::a('Книжная полка', ['/bookshelf'], ['class' => 'text-white']) ?>
        &rsaquo;
        <span class="font-weight-normal text-secondary"><?= Html::encode($this->title) ?></span>
    </p>
    <hr class="border-white" />

    <?php if (BookShelf::isEditor()): ?>
        <div class="row col">
            <div class="btn-group mt-2 mb-4">
                <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success mv-link']) ?>       
            </div>
        </div>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'pjax-calendar']) ?>
    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'showHeader' => false,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table-secondary',
        ],
        'summaryOptions' => [
            'class' => 'text-white',
        ],
        'columns' => [            
            [
                'attribute' => 'photo',
                'value' => function($model) {
                    /** @var app\modules\bookshelf\models\BookShelfCalendar $model */
                    return Html::img($model->getPhoto(), ['style' => 'width:15rem;', 'class' => 'img-thumbnail']);
                },
                'format' => 'raw',
                'options' => [
                    'style' => 'width: 17rem;',
                    'class' => 'text-center',
                ],
            ],
            [
                'attribute' => 'writer',
                'value' => function($model) {
                    /** @var app\modules\bookshelf\models\BookShelfCalendar $model */
                    $d1 = Yii::$app->formatter->asDate($model->date_birthday);
                    $d2 = Yii::$app->formatter->asDate($model->date_die);
                    return Html::tag('h1', $model->writer . '<br />' .
                        Html::tag('span', "($d1 - $d2)", ['class' => 'lead'])
                    )
                    . Html::tag('hr')
                    . Html::tag('p', $model->description);
                },
                'format' => 'raw',
            ],            
            [
                'value' => function($model) {
                    /** @var app\modules\bookshelf\models\BookShelfCalendar $model */
                    $html = Html::beginTag('div', ['class' => 'btn-group']);
                    $html .= Html::a('Изменить', ['update', 'id'=>$model->id], ['class' => 'mv-link btn btn-primary btn-sm', 'pjax' => false]);
                    $html .= Html::a('Удалить', ['delete', 'id'=>$model->id], [
                        'class' => 'btn btn-danger btn-sm mv-link',
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите удалить?',
                            'method' => 'post',
                            'pjax' => true,
                        ],
                    ]);
                    $html .= Html::endTag('div');
                    return $html;
                },
                'format' => 'raw',
                'visible' => BookShelf::isEditor(),
            ],
        ],
    ]); ?>

    <?php Pjax::end() ?>

</div>
