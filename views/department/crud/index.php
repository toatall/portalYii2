<?php

use app\models\department\Department;
use yii\bootstrap5\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\Organization $modelOrganization */

$models = $dataProvider->models;

?>
<div class="department-index card">

    <?php if (Department::isRoleModerator($modelOrganization->code ?? null)) : ?>
        <div class="card-header">
            <div class="btn-group">
                <?= Html::a('<i class="fas fa-plus"></i> Добавить отдел', 
                    ['crud-create', 'org' => $modelOrganization->code], 
                    ['class' => 'btn btn-outline-primary', 'id' => 'btn-department-create']) ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($models): ?>
       
    <div class="accordion" id="accordionDeps">
    
        <?php foreach($models as $model): 
            /** @var app\models\department\Department $model */    
        ?>

        <div class="card">
            <div class="card-header" id="head_<?= $model->id ?>">
                <div class="row">
                    <div class="col-10">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?= $model->id ?>" aria-expanded="true" aria-controls="collapse_<?= $model->id ?>">
                                <span class="lead">
                                    <?= $model->department_index ?>. <?= $model->department_name ?>
                                </span>
                            </button>
                        </h2>
                    </div>
                    <div class="col text-end pb-2">
                        <div class="btn-group">
                            <?= Html::a('<i class="fas fa-pencil"></i> Редактировать', ['crud-update', 'id'=>$model->id], ['class' => 'btn btn-primary btn-sm btn-department-update']) ?>
                            <?= Html::a('<i class="fas fa-trash"></i> Удалить', ['crud-delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger btn-sm btn-department-delete',                                
                            ]) ?>
                        </div>                        
                    </div>
                </div>
            </div>

            <div id="collapse_<?= $model->id ?>" class="collapse" aria-labelledby="head_<?= $model->id ?>" data-bs-parent="#accordionDeps" data-url="<?= Url::to(['/department/crud-cards', 'id'=>$model->id]) ?>">
                <div class="card-body" data-url="<?= Url::to(['/department/crud-cards', 'id'=>$model->id]) ?>"></div>
            </div>
        </div>

        <?php endforeach; ?>
        
    </div>
    <?php $this->registerJs(<<<JS

        $('#accordionDeps').on('shown.bs.collapse', function(event) {
            
            const target = $(event.target);
            const loaded = target.is('[loaded]');
            const div = target.children('div.card-body');
            const url = target.data('url');

            if (loaded) {
                return false;
            }

            div.html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
            
            $.get(url)
            .done(function(data) {
                div.html(data);
            })
            .fail(function (jqXHR) {
                div.html('<div class="card card-body text-danger">' + jqXHR.status + ' ' + jqXHR.statusText + '</div>');
            }); 

            target.attr('loaded', true);
        });

        $('.btn-department-delete').on('click', function() {
            if (!confirm('Вы уверены, что хотите удалить?')) {
                return false
            }

            const url = $(this).attr('href')
            
            $.post(url)
            .done(function() {
                $(window.modalViewerDepartment).trigger('onRequestJsonAfterAutoCloseModal')
            })
            
            return false
        })

        $('#btn-department-create').on('click', function() {
            let url = $(this).attr('href')
            modalViewerDepartment.showModal(url)
            return false                      
        })

        $('.btn-department-update').on('click', function() {
            let url = $(this).attr('href')
            modalViewerDepartment.showModal(url)
            return false                      
        })

    JS); ?>

    <?php endif; ?>

</div>

