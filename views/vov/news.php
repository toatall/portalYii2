<?php
/* @var $this yii\web\View */
/* @var $searchModel \app\models\news\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\widgets\ListView;
use yii\widgets\Pjax;

?>

<?php Pjax::begin(['id'=>'ajax-news-ifns', 'timeout' => false, 'enablePushState'=>false]); ?>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '/news/_list',
    'layout' => "{items}\n{pager}",
]) ?>

<?php Pjax::end(); ?>