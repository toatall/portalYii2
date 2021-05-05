<?php

use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel \app\models\zg\EmailGovermentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'База электронных адресов органов государственной власти';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="email-goverment-index row">
    <h1><?= $this->title ?></h1>
    <hr />

    <?php Pjax::begin(['id'=>'ajax-email-goverment-index', 'timeout' => false, 'enablePushState'=>false]); ?>
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
            'email',
            'post_address',
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
