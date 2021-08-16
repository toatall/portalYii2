<?php

/** @var yii\web\View $this */
/** @var app\modules\rookie\modules\photohunter\models\Photos[] $modelPhotos */

use yii\bootstrap4\Html;
use yii\helpers\Url;

$this->title = 'Проект "Фотоохота"';
?>

<section class="jumbotron text-center bg-light" style="margin-top: -100px;">
    <div class="container ">
        <img src="<?= Yii::getAlias('@content/rookie/photohunter') ?>/images/hunter1.jpg" style="height: 20em;" />
        <h1>Проект "Фотоохота"</h1>
        <p class="lead text-muted">
            Отдел обеспечения процедур банкротства
        </p>
    </div>
</section>
<div class="album bg-light">
    <div class="container">
        <div class="row">
            <?php foreach ($modelPhotos as $model) : ?>
                <div class="col-6">
                    <div class="card mb-4 shadow-sm">
                        <!--img src="<?= $model->thumb ?>" class="card-img-top"  /-->
                        <div class="card-body text-center">
                            <div class="text-center">
                                <img src="<?= $model->thumb ?>" class="img-thumbnail" style="max-height: 20em; max-width: 100%;" />
                                <hr />
                                <h4><span class="badge badge-secondary">#<?= $model->nomination ?></span></h4>
                            </div>                            
                            <p class="lead">
                                <?= $model->title ?><br />
                                <strong><?= $model->description ?></strong>
                            </p>                                                    
                            <p class="card-text"></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <?php if ($model->canVote()): ?>
                                    <a href="<?= Url::to(['/rookie/photohunter/default/vote', 'id'=>$model->id]) ?>" class="btn btn-outline-primary link-modal" data-title="<?= $model->title ?>" data-description="<?= $model->description ?>">
                                        <i class="far fa-star"></i> Проголосовать
                                    </a>
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted"></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Portfolio Modals-->
<div class="modal fade" id="modal-dialog" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title"></h1>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>            
            <div class="modal-body" style="border-top: 1px solid rgba(0,0,0,.125);">BODY</div>           
            <div class="modal-footer" style="border-top: 1px solid rgba(0,0,0,.125);">                 
                <button class="btn btn-primary" data-dismiss="modal">
                    <i class="fas fa-times fa-fw"></i>
                    Закрыть
                </button>
            </div>
        </div>        
    </div>
</div>

<?php $this->registerJs(<<<JS
       
    $('.link-modal').on('click', function() {
        const link = $(this);
        const dialog = $('#modal-dialog');
        const dialogTitle = dialog.find('.modal-title');
        const dialogBody = dialog.find('.modal-body');
        const loader = '<i class="fas fa-circle-notch fa-spin fa-2x"></i>';

        dialogTitle.html(loader);
        dialogBody.html(loader);
        dialog.modal('show');

        dialogTitle.html(link.data('description') + '<br /><small>' + link.data('title') + '</small>');
        
        $.get(link.attr('href'))
        .done(function(resp) {
            dialogBody.html(resp);
        })
        .fail(function(err) {
            dialogTitle.html('Ошибка');
            dialogBody.html('<div class="alert alert-danger">' + err.responseText + '</div>');
        });

        return false;
    });

JS);