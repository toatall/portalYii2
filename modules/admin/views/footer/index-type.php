<?php

use yii\bootstrap5\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

?>
<div class="footer-type-index">

    <?php Pjax::begin(['id'=>'pjax-footer-index', 'timeout'=>false, 'enablePushState' => false]); ?>
    
    <?= GridView::widget([
        'summary' => false,
        'tableOptions' => ['class' => 'table table-striped table-bordered small'],
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'name',           
            [
                'header' => Html::a('<i class="fas fa-plus-circle"></i>', ['create'], ['class' => 'btn btn-success btn-sm btn-create']),
                'class' => ActionColumn::class,
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function($url, $model, $key) {
                        return Html::a('<i class="fas fa-pencil"></i>', ['update', 'id' => $model->id], ['class' => 'btn-update']);
                    },
                    'delete' => function($url, $model, $key) {
                        return Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model->id], [                            
                            'class' => 'btn-delete',
                        ]);
                    },
                ],
            ],            
        ],
    ]); ?>

    <?php 
    $this->registerJs(<<<JS
        
        
        (function() {
            
            const modal = new ModalViewer()
            
            $(modal).on('onRequestJsonAfterAutoCloseModal', function(data) {       
                updateGridView()
            })

            function updateGridView() {
                $.pjax.reload({ container: '#pjax-footer-index-data-select' })
                $('#div-footer-type').data('ajax').load()
            }
            
            $('#pjax-footer-index .btn-create').on('click', function() {
                modal.showModal($(this).attr('href'))
                return false
            })
            
            $('#pjax-footer-index .btn-update').on('click', function() {
                modal.showModal($(this).attr('href'))                
                return false
            })

            $('#pjax-footer-index .btn-delete').on('click', function() {
                if (!confirm('Вы уверены, что хотите удалить раздел?')) {
                    return
                }
                const url = $(this).attr('href')
                $.post(url).done(function() { updateGridView() })
                return false
            })
            

        }())
    JS); ?>

    <?php Pjax::end() ?>

</div>


