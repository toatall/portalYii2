<?php

use kartik\grid\GridView;
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

    <?php Pjax::begin(['id'=>'ajax-email-goverment-index', 'timeout' => false]); ?>
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
    <?php Pjax::end(); ?>
</div>
