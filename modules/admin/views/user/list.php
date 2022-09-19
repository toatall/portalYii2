<?php

use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\User $searchModel */

?>
<div class="user-index">

    <?php Pjax::begin(['timeout'=>false, 'enablePushState'=>false]) ?>

    <?= GridView::widget([
        'id' => 'gridViewUserList',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'username_windows',
            'fio',
            [
                'format'=>'raw',
                'value'=>function(\app\models\User $model) {
                    return Html::button('Добавить', [
                        'class' => 'btn btn-primary btn-select-user',
                        'user_name' => $model->getConcat(),
                        'user_id' => $model->id,
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
$('.btn-select-user').on('click', function() {
    $(modalViewer).trigger('onPortalSelectUser', { id: $(this).attr('user_id'), name: $(this).attr('user_name') });
    return false;
});
JS
);
?>
    <?php Pjax::end() ?>

</div>

