<?php
/** @var \yii\web\View $this */

use app\modules\admin\assets\JsTreeAsset;
use yii\bootstrap5\Dropdown;
use yii\bootstrap5\Html;
use yii\helpers\Url;

JsTreeAsset::register($this);

$this->title = 'Главная';
?>

<div class="admin-default-index">  

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header fw-bold">
                    Разделы
                </div>
                <div class="card-body">
                    <div class="alert alert-info small">
                        <strong>Внимание!</strong>
                        Для перехода в раздел нажмите на него двойным щелчком мыши.
                    </div>
                    <div class="btn-group">
                        <?= Html::button('<i class="fas fa-refresh"></i> Обновить', ['class' => 'btn btn-primary btn-sm', 'id'=>'btn-refresh']) ?>
                        <div class="btn-group dropdown" role="dropdown">
                            <?= Html::button('<i class="fas fa-cogs"></i> Функции', [
                                'id' => 'btn-addons',
                                'class' => 'btn btn-secondary btn-sm dropdown-toggle',
                                'data-bs-toggle' => 'dropdown',
                            ]) ?>
                            <?= Dropdown::widget([
                                'encodeLabels' => false,
                                'items' => [
                                    [
                                        'label' => '<i class="fas fa-plus-circle"></i> Добавить раздел', 
                                        'url' => ['create'],
                                        'linkOptions' => ['id' => 'btn-create'],
                                    ],
                                    [
                                        'label' => '<i class="fas fa-pencil"></i> Изменить', 
                                        'url' => ['#'],
                                        'linkOptions' => ['id' => 'btn-update', 'class' => 'btn-selected-tree-node'],
                                    ],
                                    [
                                        'label' => '<i class="fas fa-trash"></i> Удалить', 
                                        'url' => ['#'],
                                        'linkOptions' => ['id' => 'btn-delete', 'class' => 'btn-selected-tree-node'],
                                    ],
                                ],
                            ]) ?>
                        </div>                        
                    </div>
                    <hr />
                    <div id="js-tree"></div>
                </div>               
                
                <?php 
                $url = Url::to(['/admin/tree/tree']);
                $this->registerJs(<<<JS

                    $('.btn-selected-tree-node').hide();

                    $('#js-tree').jstree({
                        core: { 
                            'multiple': false,
                            'data': {
                                'url': '$url',                                
                            },
                            'strings': {
                                'Loading ...': 'Загрузка ...',
                            }
                        },                         
                        plugins: ['types', 'themes']                        
                    })
                    .on('select_node.jstree', function(node, s) {
                        $('.btn-selected-tree-node').show();
                    })
                    .on('dblclick.jstree', function(e) {
                        let url = $(e.target).attr('href');
                        if (url && url != '#') {
                            window.location.href = url;
                        }
                        return false;
                    })
                    .bind('deselect_all.jstree', function(e, data) {
                        $('.btn-selected-tree-node').hide();
                    });

                    $('#btn-refresh').on('click', function() {
                        $('#js-tree').jstree().refresh();
                    });
                    
                    var modalViewer = new ModalViewer({
                        bindFormSelector: '#form-tree',
                    })
                    $(modalViewer).on('onRequestJsonAfterAutoCloseModal', function() {
                        $('#btn-refresh').trigger('click')
                        $('.btn-selected-tree-node').hide()
                    })

                    // создание раздела
                    $('#btn-create').on('click', function() {
                        let url = $(this).attr('href')
                        const selected = $('#js-tree').jstree().get_selected(true)[0] ?? null     
                        if (selected != null) {
                            url = UrlHelper.addParam(url, { idParent: selected.original.idNode })
                        }
                        modalViewer.showModal(url)
                        return false                        
                    })
                    
                    // изменение раздела
                    $('#btn-update').on('click', function() {
                        const selected = $('#js-tree').jstree().get_selected(true)[0] ?? null                        
                        if (selected) {
                            const url = selected.original.urlUpdate
                            modalViewer.showModal(url)                           
                        }
                        return false
                    })

                    // удаление раздела
                    $('#btn-delete').on('click', function() {                        
                        const selected = $('#js-tree').jstree().get_selected(true)[0] ?? null                        
                        if (selected) {
                            const url = selected.original.urlDelete
                            console.log(selected.original);
                            if (!confirm('Вы уверены, что хотите удалить раздел "' + selected.original.name + '"?')) {
                                return false;
                            }
                            $.ajax({
                                method: 'post',
                                url: url
                            })
                            .done(function(data) {
                                $('#btn-refresh').trigger('click')
                                $('.btn-selected-tree-node').show()
                            });
                        }
                        return false
                    })

                JS); ?>

            </div>
        </div>       
    </div>
   
</div>