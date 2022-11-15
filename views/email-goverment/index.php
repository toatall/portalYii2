<?php

use app\models\zg\EmailGoverment;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\zg\EmailGovermentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'База электронных адресов органов государственной власти';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="email-goverment-index row">
    
    <div class="col border-bottom mb-2">
        <p class="display-5">
            <?= $this->title ?>
        </p>    
    </div>
        
    <?php Pjax::begin(['id'=>'pjax-email-goverment-index', 'timeout' => false]); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => [
            'style' => 'table-layout:fixed',
        ],
        'pager' => [
            'firstPageLabel' => 'Первая',
            'lastPageLabel' => 'Последняя',
        ],
        'columns' => [
            ['class' => \yii\grid\SerialColumn::class],

            'org_name',
            'ruk_name',
            'telephone',
            [
                'attribute' => 'email',
                'format' => 'raw',
            ],
            'post_address',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, EmailGoverment $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'buttonOptions' => [
                    'class' => 'mv-link',
                ],
                'visibleButtons' => [
                    'view' => false,
                    'update' => EmailGoverment::isModerator(),
                    'delete' => EmailGoverment::isModerator(),
                ],               
            ],
        ],
        'toolbar' => [            

            'content' => (EmailGoverment::isModerator())
                ? Html::a('Добавить', ['create'], ['class' => 'btn btn-success mx-2 mv-link']) : '',

            '{export}',
            '{toggleData}',
        ],
        'export' => [
            'showConfirmAlert' => false,
        ],
        'panel' => [
            'type' => GridView::TYPE_LIGHT,       
        ],
    ]); ?>

    <?php $this->registerJs(<<<JS
        $(modalViewer).off('onRequestJsonAfterAutoCloseModal');
        $(modalViewer).on('onRequestJsonAfterAutoCloseModal', function(event, data) {    
            $.pjax.reload({ container: '#pjax-email-goverment-index'});        
        });
    JS); ?>

    <?php Pjax::end(); ?>
</div>
