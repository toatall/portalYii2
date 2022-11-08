<?php

use app\models\MigrantsQuestionnation;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\MigrantsQuestionnationSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Анкетирование мигрантов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="migrants-questionnation-index">

    <h1 class="display-5 border-bottom"><?= Html::encode($this->title) ?></h1>

    <?php if (MigrantsQuestionnation::isModerator()): ?>
    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success mv-link']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id'=>'pjax-migrants-questionnation-index', 'timeout'=>false ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'ul_name',
            'ul_inn',
            'ul_kpp',
            'date_send_notice:date',
            'region_migrate',
            'cause_migrate',
            //'date_create',
            //'date_update',
            //'author',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, MigrantsQuestionnation $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'buttonOptions' => [
                    'class' => 'mv-link',
                ],
                'visibleButtons' => [
                    'update' => MigrantsQuestionnation::isModerator(),
                    'delete' => MigrantsQuestionnation::isModerator(),
                ],
            ],
        ],
    ]); ?>

    <?php $this->registerJs(<<<JS
    
    $(modalViewer).off('onRequestJsonAfterAutoCloseModal');
    $(modalViewer).on('onRequestJsonAfterAutoCloseModal', function(event, data) {    
        $.pjax.reload({ container: '#pjax-migrants-questionnation-index'});        
    });
    
    JS); ?>
    <?php Pjax::end(); ?>

</div>
