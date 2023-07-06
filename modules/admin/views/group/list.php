<?php

use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var \app\models\Group $searchModel */

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

<?php
$this->registerJs(<<<JS
    $('.btn-select-group').on('click', function() {   
        console.log($(this).parents('.modal'))

        // const modal = $(this).parents('div.modal').data('mv');
        // $(modal).trigger('onPortalSelectGroup', { id: $(this).attr('group_id'), name: $(this).attr('group_name') });
        return false;
    });
JS
);
?>

    <?php Pjax::end() ?>

</div>

