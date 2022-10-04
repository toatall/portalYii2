<?php

use kartik\grid\GridView;
use app\modules\admin\models\Role;
use yii\bootstrap5\Html;
use app\models\User;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var Role $model */

?>
<div class="mt-2">

    <?php Pjax::begin(['enablePushState'=>false, 'id'=>'pjax-user-container']) ?>
    <?= GridView::widget([
        'dataProvider' => $model->getChildUserDataProvider(),
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'id',
            'default_organization',
            'username',            
            'fio',
            'date_create:datetime',
            'date_edit:datetime',
            [                
                'format'=>'raw',
                'value'=>function(User $m) use ($model) {
                    return Html::a('<i class="fas fa-trash-alt"></i> Удалить', 
                        ['/admin/role/delete-sub-user', 'id' => $model->name, 'userId' => $m->id], 
                        [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить?',
                                'method' => 'post',
                            ],
                        ]);
                },
            ],
        ],
        'toolbar' => [
            [
                'content' => '<div class="btn-group me-3">'
                    . Html::a('<i class="fas fa-plus-circle"></i> Добавить пользователя', 
                        ['/admin/user/list', 'role'=>$model->name], ['class'=>'btn btn-outline-secondary mv-link'])
                    . Html::button('<i class="fas fa-sync-alt"></i> Обновить', ['id' => 'btn-refresh-user-container', 'class' => 'btn btn-outline-secondary'])
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

<?php
$this->registerJs(<<<JS
    $('#btn-refresh-user-container').on('click', function () {
        $.pjax.reload({container:'#pjax-user-container', async: false });
        return false;
    });
JS
);

$urlAddUser = Url::to(['/admin/role/add-sub-user', 'id'=>$model->name]);
$this->registerJs(<<<JS
       
    // событие, если пользователь выбран
    $(modalViewer).on('onPortalSelectUser', function(event, data) {
       
        $.ajax({
            url: '$urlAddUser',
            data: { userId: data.id },
            method: 'get'
        })
        .done(function(data) {            
            $.pjax.reload({container:'#pjax-user-container', async: false });
        })
        .fail(function(err) {
            const toast = $('#toast-alert-danger');
            toast.find('.toast-title').html('Ошибка');
            toast.find('.toast-body').html(err.responseText);
            toast.toast('show');
        });        
            
        modalViewer.closeModal();        
    });    

JS); 
?>

    <?php Pjax::end(); ?>

</div>

