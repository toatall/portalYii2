<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \app\models\Group */

?>
<div class="group-index">

    <?php Pjax::begin(['timeout'=>false, 'enablePushState'=>false]) ?>

    <?= GridView::widget([
        'id' => 'gridViewGroupList',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            //'id_organization',
            'name',
            //'description',
            [
                'format'=>'raw',
                'value'=>function(\app\models\Group $model) {
                    return Html::button('Добавить', [
                        'class' => 'btn btn-primary btn-select-group',
                        'group_name' => $model->name,
                        'group_id' => $model->id,
                    ]);
                },
            ],

        ],
    ]); ?>

<?php
// ЛЕВОЕ МЕНЮ
$this->registerJs(<<<JS
$('.btn-select-group').on('click', function() {
    $(modalViewer).trigger('onPortalSelectGroup', { id: $(this).attr('group_id'), name: $(this).attr('group_name') });
    return false;
});
JS
);
?>

    <?php Pjax::end() ?>

</div>

