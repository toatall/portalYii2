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
            [
                'attribute' => 'ul_name',
                'label' => $searchModel->getAttributeLabel('ul_name') . ' <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-title="Наименование ЮЛ, представивших в ЕЦР (86038) заявления о предстоящем изменении адреса места нахождения (в иной субъект РФ)"></i>',                
                'encodeLabel' => false,
            ],            
            'ul_inn',
            'ul_kpp',            
            [
                'attribute' => 'date_send_notice',
                'label' => $searchModel->getAttributeLabel('date_send_notice') . ' <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-title="Дата направления уведомления о смене адреса в регистрирующий орган"></i>',
                'encodeLabel' => false,
                'format' => 'date',
            ],
            'region_migrate',
            [
                'attribute' => 'region_migrate',
                'label' => $searchModel->getAttributeLabel('region_migrate') . ' <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-title="Регион РФ, в который планирует мигрировать ЮЛ"></i>',
                'encodeLabel' => false,
            ],
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
    
    $('.migrants-questionnation-index [data-bs-toggle="tooltip"]').tooltip();

    JS); ?>
    <?php Pjax::end(); ?>

</div>
