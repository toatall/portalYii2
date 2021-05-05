<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \app\models\User */

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
    ]); ?>

<?php
// ЛЕВОЕ МЕНЮ
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

