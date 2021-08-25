<?php

use yii\bootstrap4\Html;
use app\modules\admin\assets\JsTreeAsset;

JsTreeAsset::register($this);

/** @var yii\web\View $this */
/** @var array $tree */

$this->title = 'Структура';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tree-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="card">
        <div class="card-header">
            <div class="btn-group">
                <?= Html::a('Добавить раздел', ['create'], ['class' => 'btn btn-success']) ?>
                <?= Html::button('Изменить', ['class' => 'btn btn-primary btn-selected-tree-node', 'id' => 'btn-update-node']) ?>
                <?= Html::button('Удалить', ['class' => 'btn btn-danger btn-selected-tree-node', 'id' => 'btn-delete-node']) ?>
            </div>
        </div>
        <div class="card-body">
            <div id="tree-view">
                <?= $tree ?>
            </div>
        </div>
    </div>


</div>
<?php

$this->registerJs(<<<JS
    // скрыть кнопки изменения и удаления
    $('.btn-selected-tree-node').hide();

    // инициализация дерева
    $('#tree-view').jstree({'core': { 'multiple': false }})
        .bind('select_node.jstree', function(e, data) {
            $('.btn-selected-tree-node').show();
        })
        .bind('deselect_all.jstree', function(e, data) {
            $('.btn-selected-tree-node').hide();
        }); 
    
    // изменение узла
    $('#btn-update-node').on('click', function() {
          let selected = $('#tree-view').jstree().get_selected();
          if (selected != '') {
              window.location = $('#' + selected).attr('data-url-update');
          }
    });
    
    // удаление узла
    $('#btn-delete-node').on('click', function() {
        let selected = $('#tree-view').jstree().get_selected();
        if (selected != '') {
            if (!confirm('Вы уверены, что хотите удалить "' + $('#' + selected).attr('data-node-name') + '"?')) {
                return false;
            }
            $.post($('#' + selected).attr('data-url-delete'), {});
        }
    });
JS
);

?>