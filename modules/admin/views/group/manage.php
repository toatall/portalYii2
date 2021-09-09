<?php

use yii\bootstrap4\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\Group $model */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = "Управление группой \"{$model->name}\"";
$this->params['breadcrumbs'][] = ['label' => 'Группы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1 class="display-4 border-bottom"><?= $this->title ?></h1>

<div class="btn-group mb-2">
    <?= Html::a('Добавить пользователя', 
        ['/admin/user/list', 'idGroup'=>$model->id], ['class'=>'btn btn-success mv-link', 'id'=>'btn-add']) ?>
    <?= Html::a('Назад', ['/admin/group/index'], ['class' => 'btn btn-secondary']) ?>
</div>

<?php Pjax::begin(['id' => 'pjax-group-manage', 'timeout' => false, 'enablePushState' => false]) ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'username',
            'fio',            
            [
                'format' => 'raw',
                'value' => function($modelUser) use ($model) {
                    return Html::a('Удалить', ['/admin/group-manage/delete', 'idGroup'=>$model->id, 'idUser'=>$modelUser->id], ['class'=>'btn btn-danger btn-delete']);
                },
            ],
        ],
    ]) ?>
 
 <?php $this->registerJs(<<<JS
    // удаление пользователя из группы
    $('.btn-delete').on('click', function() {
        if (!confirm('Вы уверены, что хотите исключить пользователя из группы?')) {
            return false;
        }
        const btnDelete = $(this);        
        btnDelete.prepend('<span class="spinner-border spinner-border-sm"></span> ');
        btnDelete.addClass('disabled'); 
        
        $.ajax({
            url: btnDelete.attr('href'),
            method: 'post'
        })
        .done(function(data) {
            $.pjax.reload('#pjax-group-manage', { async: false });
        })
        .fail(function(err) {
            const toast = $('#toast-alert-danger');
            toast.find('.toast-title').html('Ошибка');
            toast.find('.toast-body').html(err.responseText);
            toast.toast('show');
        })
        .always(function() {
            btnDelete.children('span').remove();
            btnDelete.removeClass('disabled');
        });

        return false;
    });
 JS); ?>

<?php Pjax::end() ?>

<?php 
$urlAddUser = Url::to(['/admin/group-manage/add', 'idGroup'=>$model->id]);
$this->registerJs(<<<JS
       
    // событие, если пользователь выбран
    $(modalViewer).on('onPortalSelectUser', function(event, data) {
        const btnAdd = $('#btn-add');        
        btnAdd.prepend('<span class="spinner-border spinner-border-sm"></span> ');
        btnAdd.addClass('disabled');        
        $.ajax({
            url: '$urlAddUser',
            data: { idUser: data.id },
            method: 'get'
        })
        .done(function(data) {            
            $.pjax.reload('#pjax-group-manage', { async: false });
        })
        .fail(function(err) {
            const toast = $('#toast-alert-danger');
            toast.find('.toast-title').html('Ошибка');
            toast.find('.toast-body').html(err.responseText);
            toast.toast('show');
        })
        .always(function() {
            btnAdd.children('span').remove();
            btnAdd.removeClass('disabled');
        });
            
        modalViewer.closeModal();        
    });    

JS); ?>