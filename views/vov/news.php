<?php
/** @var yii\web\View $this */
/** @var app\models\news\NewsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\bootstrap5\LinkPager;
use yii\widgets\ListView;
use yii\widgets\Pjax;

?>

<?php Pjax::begin(['id'=>'ajax-news-ifns', 'timeout' => false, 'enablePushState'=>false]); ?>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '/news/_list',
    'layout' => "{items}\n{pager}",
    'pager' => [
        'class' => LinkPager::class,
        'options' => [
            'class' => 'pt-2',
        ],
    ],
]) ?>

<?php Pjax::end(); ?>