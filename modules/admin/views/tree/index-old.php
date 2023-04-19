<?php

use yii\bootstrap5\Html;
use app\modules\admin\assets\JsTreeAsset;
use yii\widgets\Pjax;

JsTreeAsset::register($this);

/** @var yii\web\View $this */
/** @var array $tree */

$this->title = 'Структура';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(['id' => 'pjax-admin-tree-index', 'enablePushState' => false, 'timeout' => false]) ?>
<div class="tree-index">   

    <div class="col">
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

</div>
<?php

$this->registerJs(<<<JS
    // скрыть кнопки изменения и удаления
    $('.btn-selected-tree-node').hide();

    // инициализация дерева
    $('#tree-view').jstree({
        core: { 'multiple': false },
        // types: {
        //     'f-open': {
        //         'icon': 'far fa-folder-open'
        //     },
        //     'f-closed': {
        //         'icon': 'far fa-folder'
        //     },
        //     'default': {
        //         'icon': 'far fa-folder'
        //     }
        // },
        // plugins: ['types', 'themes']
    })
    .bind('select_node.jstree', function(e, data) {
        $('.btn-selected-tree-node').show();        
    })
    .bind('deselect_all.jstree', function(e, data) {
        $('.btn-selected-tree-node').hide();
    });
    // $('#tree-view').on('open_node.jstree', function(e, data) { data.instance.set_type(data.node, 'f-open') });
    // $('#tree-view').on('close_node.jstree', function(e, data) { data.instance.set_type(data.node, 'f-closed') }); 
    
    
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

$this->registerCss(<<<CSS
    .jstree-default a {
        white-space: normal !important;
        height: auto;
    }
    .jstree-anchor {
        height: auto !important;
    }
    .jstree-default li > ins {
        vertical-align: top;
    }
    .jstree-leaf {
        height: auto;
    }
    .jstree-leaf a {
        height: auto !important;
    }

CSS); 
?>
<?php Pjax::end() ?>