<?php
/** @var \yii\web\View $this */

use app\models\Tree;
use app\modules\admin\assets\JsTreeAsset;

JsTreeAsset::register($this);

$this->title = 'Главная';
?>

<div class="admin-default-index">  

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Разделы
                </div>
                <div id="tree-view" class="card-body">
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