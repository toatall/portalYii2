<?php

use kartik\grid\GridView;
use app\modules\admin\models\Role;
use yii\bootstrap4\Html;
use app\models\User;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var Role $model */

?>
<div style="padding-top: 5px;">

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="btn-group" role="group">
                <?= Html::a('<i class="fas fa-plus-circle"></i> Добавить пользователя', ['/admin/role/add-sub-user', 'id'=>$model->name], ['class'=>'btn btn-secondary mv-link']) ?>
                <button id="btn-refresh-user-container" class="btn btn-secondary"><i class="fas fa-sync-alt"></i> Обновить</button>
            </div>
        </div>
    </div>

    <?php Pjax::begin(['enablePushState'=>false, 'id'=>'pjax-user-container']) ?>
    <?= GridView::widget([
        'dataProvider' => $model->getChildUserDataProvider(),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'username',
            'username_windows',
            'date_create:datetime',
            'date_edit:datetime',
            [
                /* @todo сделать удаление через ajax */
                'format'=>'raw',
                'value'=>function(User $m) use ($model) {
                    return Html::a('<i class="fas fa-trash-alt"></i> Удалить', ['/admin/role/delete-sub-user', 'id' => $model->name, 'userId' => $m->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите удалить?',
                            'method' => 'post',
                        ],
                    ]);
                },
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>
<?php
$this->registerJs(<<<JS
    $('#btn-refresh-user-container').on('click', function () {
        $.pjax.reload({container:'#pjax-user-container', async: false });
        return false;
    });
JS
);
?>
