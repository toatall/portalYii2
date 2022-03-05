<?php

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
<div class="book-shelf-calendar-index">

    <p class="display-4 border-bottom">
        <?= Html::encode($this->title) ?>
    </p>

    <?php if (Yii::$app->user->can('admin')): ?>
    <div class="btn-group mt-2 mb-2">
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-outline-success btn-sm mv-link']) ?>       
    </div>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'pjax-calendar']) ?>
    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'showHeader' => false,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
                    $html .= Html::a('Изменить', ['update', 'id'=>$model->id], ['class' => 'mv-link btn btn-outline-primary btn-sm', 'pjax' => false]);
                    $html .= Html::a('Удалить', ['delete', 'id'=>$model->id], [
                        'class' => 'btn btn-outline-danger btn-sm mv-link',
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
                'visible' => Yii::$app->user->can('admin'),
            ],
        ],
    ]); ?>

    <?php Pjax::end() ?>

</div>
