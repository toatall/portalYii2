<?php

use yii\grid\GridView;
use app\modules\admin\models\Role;
use yii\bootstrap\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model Role */

?>
<div style="padding-top: 5px;">

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="btn-group" role="group">
                <?= Html::a('<i class="fas fa-plus-circle"></i> Добавить роль', ['/admin/role/add-sub-role', 'id'=>$model->name], ['class'=>'btn btn-default mv-link']) ?>
                <button id="btn-refresh-role-container" class="btn btn-default"><i class="fas fa-sync-alt"></i> Обновить</button>
            </div>
        </div>
    </div>

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