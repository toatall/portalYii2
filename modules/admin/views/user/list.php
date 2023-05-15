<?php

use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\User $searchModel */

?>
<h2 class="title mv-hide">Пользователи</h2>
<div class="user-index">

    <?php Pjax::begin(['timeout'=>false, 'enablePushState'=>false]) ?>

    <?= GridView::widget([
        'id' => 'gridViewUserList',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'default_organization',
            'username_windows',
            'fio',
            'department',
            [
                'label' => 'Актуальность',
                'attribute' => 'user_disabled_ad',                
                'format' => 'raw',
                'value' => function(\app\models\User $model) {
                    if ($model->user_disabled_ad) {
                        return Html::tag('span', 
                            '<i class="fas fa-times"></i> Учетная запись заблокирована (' . $model->description_ad . ')',
                            ['class' => 'text-danger']);
                    }
                    else {
                        return Html::tag('span', 
                            '<i class="fas fa-check"></i> Действующая учетная запись', ['class' => 'text-success']);
                    }
                },
                'filter' => ['0' => 'Актуальные', '1' => 'Не актуальные'],
            ],
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
        'rowOptions' => function($model, $key, $index, $grid) {
            /** @var app\models\User $model */
            if ($model->user_disabled_ad) {
                return ['class' => 'table-danger'];
            }
        },
    ]); ?>

<?php
$this->registerJs(<<<JS
$('.btn-select-user').on('click', function() {
    const modal = $(this).parents('div.modal').data('mv');
    $(modal).trigger('onPortalSelectUser', { id: $(this).attr('user_id'), name: $(this).attr('user_name') });
    return false;
});
JS
);
?>
    <?php Pjax::end() ?>

</div>

