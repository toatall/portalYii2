<?php

use yii\helpers\Html;
use app\models\menu\Menu;
use app\modules\admin\assets\JsTreeAsset;

JsTreeAsset::register($this);

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Меню';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">Верхнее меню</div>
        <div class="panel-body">
            <div class="btn-group">
                <?= Html::a('Добавить пункт', ['create', 'typeMenu' => Menu::POSITION_MAIN], ['class' => 'btn btn-success']) ?>
                <?= Html::button('Изменить', ['class' => 'btn btn-primary btn-selected-main-tree-node', 'id' => 'btn-update-node-main']) ?>
                <?= Html::button('Удалить', ['class' => 'btn btn-danger btn-selected-main-tree-node', 'id' => 'btn-delete-node-main']) ?>
            </div>
            <hr />
            <div id="tree-view-main">
                <?= Menu::topTree() ?>
            </div>
        </div>
    </div>
<?php
// ГЛАВНОЕ МЕНЮ
$this->registerJs(<<<JS
    // скрыть кнопки изменения и удаления
    $('.btn-selected-main-tree-node').hide();

    // инициализация дерева
    $('#tree-view-main').jstree({'core': { 'multiple': false }})
        .bind('select_node.jstree', function(e, data) {
            $('.btn-selected-main-tree-node').show();
        })
        .bind('deselect_all.jstree', function(e, data) {
            $('.btn-selected-main-tree-node').hide();
        }); 
    
    // изменение узла
    $('#btn-update-node-main').on('click', function() {
          let selected = $('#tree-view-main').jstree().get_selected();
          if (selected != '') {
              window.location = $('#' + selected).attr('data-url-update');
          }
    });
    
    // удаление узла
    $('#btn-delete-node-main').on('click', function() {
        if (!confirm('Вы уверены, что хотите удалить?')) {
            return false;
        }
        let selected = $('#tree-view-main').jstree().get_selected();
        if (selected != '') {
            $.post($('#' + selected).attr('data-url-delete'), {});
        }
    });
JS
);
?>

    <div class="panel panel-default">
        <div class="panel-heading">Левое меню</div>
        <div class="panel-body">
            <div class="btn-group">
                <?= Html::a('Добавить пункт', ['create', 'typeMenu' => Menu::POSITION_LEFT], ['class' => 'btn btn-success']) ?>
                <?= Html::button('Изменить', ['class' => 'btn btn-primary btn-selected-left-tree-node', 'id' => 'btn-update-node-left']) ?>
                <?= Html::button('Удалить', ['class' => 'btn btn-danger btn-selected-left-tree-node', 'id' => 'btn-delete-node-left']) ?>
            </div>
            <hr />
            <div id="tree-view-left">
                <?= Menu::leftTree() ?>
            </div>
        </div>
    </div>

</div>
<?php
// ЛЕВОЕ МЕНЮ
$this->registerJs(<<<JS
    // скрыть кнопки изменения и удаления
    $('.btn-selected-left-tree-node').hide();

    // инициализация дерева
    $('#tree-view-left').jstree({'core': { 'multiple': false }})
        .bind('select_node.jstree', function(e, data) {
            $('.btn-selected-left-tree-node').show();
        })
        .bind('deselect_all.jstree', function(e, data) {
            $('.btn-selected-left-tree-node').hide();
        }); 
    
    // изменение узла
    $('#btn-update-node-left').on('click', function() {
          let selected = $('#tree-view-left').jstree().get_selected();
          if (selected != '') {
              window.location = $('#' + selected).attr('data-url-update');
          }
    });
    
    // удаление узла
    $('#btn-delete-node-left').on('click', function() {
        if (!confirm('Вы уверены, что хотите удалить?')) {
            return false;
        }
        let selected = $('#tree-view-left').jstree().get_selected();
        if (selected != '') {
            $.post($('#' + selected).attr('data-url-delete'), {});
        }
    });
JS
);

?>