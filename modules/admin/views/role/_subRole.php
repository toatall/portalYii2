<?php

use kartik\grid\GridView;
use app\modules\admin\models\Role;
use yii\bootstrap5\Html;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var Role $model */

?>
<div class="mt-2">    

    <?php Pjax::begin(['enablePushState'=>false, 'id'=>'pjax-role-container']) ?>
    <?= GridView::widget([
        'dataProvider' => $model->getChildRolesDataProvider(),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'description',
            'created_at:datetime',
            'updated_at:datetime',
            [
                /* @todo сделать удаление через ajax */
                'attribute' => '',
                'value' => function(Role $m) use ($model) {
                    return Html::a('<i class="fas fa-trash-alt"></i> Удалить',
                        ['/admin/role/delete-sub-role', 'id'=>$model->name, 'roleId'=>$m->name],
                        [
                            'class'=>'btn btn-danger',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить?',
                                'method' => 'post',
                            ],
                        ]);
                },
                'format'=>'raw',
            ],
        ],
        'toolbar' => [
            [
                'content' => '<div class="btn-group me-3">'
                    . Html::a('<i class="fas fa-plus-circle"></i> Добавить роль', 
                        ['/admin/role/add-sub-role', 'id'=>$model->name], ['class'=>'btn btn-outline-secondary mv-link'])
                    . Html::button('<i class="fas fa-sync-alt"></i> Обновить', 
                        ['id' => 'btn-refresh-role-container', 'class' => 'btn btn-outline-secondary'])
                .'</div>',
            ],
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
    <?php Pjax::end(); ?>

</div>
<?php
$this->registerJs(<<<JS
    $('#btn-refresh-role-container').on('click', function () {
        $.pjax.reload({container:'#pjax-role-container', async: false });
        return false;
    });
JS
);
?>