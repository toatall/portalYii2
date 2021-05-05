<?php

use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $modelDepartment \app\models\department\Department */
/* @var $breadcrumbs array */

$this->params['breadcrumbs'][] = ['label' => $modelDepartment->department_name, 'url' => ['view', 'id'=>$modelDepartment->id]];
$this->params['breadcrumbs'] = ArrayHelper::merge($this->params['breadcrumbs'], $breadcrumbs);
?>

<div class="news-index row">

    <h2 class="text-center" style="font-weight: bolder;"><?= $this->title ?></h2>

    <?php Pjax::begin(['id'=>'ajax-page', 'timeout' => false, 'enablePushState'=>false]); ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '/news/_list',
        'layout' => "{items}\n{pager}",
    ]) ?>

    <?php Pjax::end(); ?>

</div>
<?php
$this->registerJs(<<<JS
    // так как на главной странице 2 колонки с новостями
    // используется для миниатюры 3 пункта, т.к. мелкая получается миниатюра,
    // а здесь одна колонка и миниатюра получается большая, поэтому уменьшаем    
    $('.left-content')
        .removeClass('col-md-3').addClass('col-md-2')
        .removeClass('col-sm-3').addClass('col-sm-2')
        .removeClass('col-lg-3').addClass('col-lg-2');
JS
);
?>