<?php

use yii\bootstrap5\LinkPager;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\department\Department $modelDepartment */
/** @var array $breadcrumbs */

$this->params['breadcrumbs'][] = ['label' => 'Отделы', 'url' => ['/department/index']];
$this->params['breadcrumbs'][] = ['label' => $modelDepartment->department_name, 'url' => ['view', 'id'=>$modelDepartment->id]];
$this->params['breadcrumbs'] = ArrayHelper::merge($this->params['breadcrumbs'], $breadcrumbs);
?>

<div class="news-index">

    <div class="col border-bottom mb-2">
        <p class="display-5">
            <?= $this->title ?>
        </p>    
    </div>
    
    <div class="">
    <?php Pjax::begin(['id'=>'ajax-page', 'timeout' => false, 'enablePushState'=>false]); ?>

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
    </div>

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