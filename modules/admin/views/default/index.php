<?php
/** @var \yii\web\View $this */

use app\models\Tree;
use app\modules\admin\assets\JsTreeAsset;
use yii\bootstrap5\Html;
use yii\helpers\Url;

JsTreeAsset::register($this);

$this->title = 'Главная';
die('not wr');
?>

<div class="admin-default-index">  

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header fw-bold">
                    Разделы
                </div>
                <div id="tree-view" class="card-body">
                    <div class="alert alert-info small">
                        Для перехода в раздел нажмите на него двойным щелчком мыши
                    </div>
                    <?php /*
                    <?= Tree::getTreeForMain() ?>
                    <?php $this->registerJs(<<<JS
                        $('#tree-view').jstree({
                            core: { 'multiple': false },                         
                            plugins: ['types', 'themes']
                        })
                        .bind('select_node.jstree', function(e, data) {                   
                            let url = $('#' + data.selected).attr('data-url-view');                    
                            let container = $('#container-tree');
                            container.html('<div class="d-flex justify-content-center">'
                                + '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>'
                                + '</div>');
                            if (url != '#') {
                                // $.get(url)
                                // .done(function(data) { container.html(data); })
                                // .fail(function(err) { container.html('<div class="text-danger">' + err.responseText + '</div>') });
                                window.location.href = url;
                            }
                            else {
                                // container.html('');
                            }


                            return false;
                        });                         
                    JS); 
                    $this->registerCss(<<<CSS
                        .jstree-default a {
                            white-space: normal !important;
                            height: auto;
                            margin-right: 20px;
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
                </div>
                */ ?>
                <div class="btn-group">
                    <?= Html::button('<i class="fas fa-refresh"></i> Обновить', ['class' => 'btn btn-secondary btn-sm', 'id'=>'btn-update']) ?>
                    <?= Html::a('Добавить раздел', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
                    <?= Html::button('Изменить', ['class' => 'btn btn-primary btn-sm btn-selected-tree-node', 'id' => 'btn-update-node']) ?>
                    <?= Html::button('Удалить', ['class' => 'btn btn-danger btn-sm btn-selected-tree-node', 'id' => 'btn-delete-node']) ?>
                </div>
                <hr />
                <div id="js-tree"></div>
                <?php 
                $url = Url::to(['/admin/tree/tree']);
                $this->registerJs(<<<JS

                    $('.btn-selected-tree-node').hide();

                    $('#js-tree').jstree({
                        core: { 
                            'multiple': false,
                            'data': {
                                'url': '$url',                                
                            }
                        },                         
                        plugins: ['types', 'themes']                        
                    })
                    .on('select_node.jstree', function(node, s) {
                        $('.btn-selected-tree-node').show();   
                    })
                    .on('dblclick.jstree', function(e) {
                        let url = $(e.target).attr('href');
                        if (url) {
                            window.location.href = url;
                        }
                        return false;
                    })
                    .bind('deselect_all.jstree', function(e, data) {
                        $('.btn-selected-tree-node').hide();
                    });

                    $('#btn-update').on('click', function() {
                        $('#js-tree').jstree().refresh();
                    });


                    // изменение узла
                    let modalViewer = new ModalViewer();
                    $('#btn-update-node').on('click', function() {
                        const selected = $('#js-tree').jstree().get_selected();
                        if (selected != '') {                           
                            const url = $('#' + selected).attr('data-url-update');
                            modalViewer.showModal(url);
                            $(modalViewer).on('onRequestJsonAfterAutoCloseModal', function() {
                                $('#btn-update').trigger('click');
                            });
                        }
                    });

                    // удаление узла
                    $('#btn-delete-node').on('click', function() {
                        
                        // let selected = $('#js-tree').jstree().get_selected();
                        // if (selected != '') {
                        //     if (!confirm('Вы уверены, что хотите удалить "' + $('#' + selected).attr('data-node-name') + '"?')) {
                        //         return false;
                        //     }
                        //     $.post($('#' + selected).attr('data-url-delete'), {});
                        // }
                    });



                    
                JS); ?>

            </div>
        </div>
        <!-- <div class="col">
            <div class="card">
                <div class="card-header">Содержимое</div>                          
                    <div class="card-body" id="container-tree"></div>
            </div>
        </div> -->
    </div>

   
</div>