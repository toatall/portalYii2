<?php

/** @var yii\web\View $this */
/** @var app\modules\rookie\modules\fortboyard\models\FortBoyard $questionToday */

use yii\helpers\Url;
use app\modules\rookie\modules\photohunter\assets\ViewerjsAsset;
use yii\bootstrap4\Html;

ViewerjsAsset::register($this);

$this->title = 'Проект "Фотоохота"';
?>

<section class="jumbotron text-center bg-light" style="margin-top: -100px; background-color: #22140b !important; padding-bottom: 0;">
    <!-- <div class="container" style="background-color: #22140b;">
        <img src="<?= Yii::getAlias('@content/rookie/photohunter') ?>/images/hunter1.png" style="height: 20em;" />
        <h1>Конкурс "Фотоохота"</h1>
        <p class="lead text-muted">
            Отдел обеспечения процедур банкротства
        </p>
    </div> -->
    <img src="/public/content/rookie/fortboyard/kisspng-fort-boyard-television-show-france-tv-game-show-fr-5b3eb1905dace6.6035552615308353443837.png" style="height: 20rem;" />
</section>

<div class="album bg-light" style="background-color: #22140b !important;">
    <div class="container">
        <div class="row">
            <?php if ($questionToday != null): ?>
            <div class="col-12 bg-dark rounded shadow text-white pb-4">
                <p class="fa-3x font-weight-bolder border-bottom text-center">Задание на сегодня</p>
                <h4><?= $questionToday->title ?></h4>
                <p><?= $questionToday->text ?></p>
                <?php if ($questionToday->isRight()): ?>
                <hr class="bg-white" />
                <?= Html::beginForm(Url::to(['/rookie/fortboyard/default/save-answer', 'id'=>$questionToday->id])) ?>
                <div class="row">
                    <div class="col-2">
                        <?= Html::label('Ваш ответ', 'input-answer') ?>
                    </div>
                    <div class="col">
                        <?= Html::textInput('answer', '', ['class' => 'form-control', 'id'=>'input-answer', 'required' => true]) ?> 
                    </div>
                    <div class="col-2">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>               
                    </div>
                </div>
                <?= Html::endForm() ?>
                <?php elseif (Yii::$app->session->hasFlash('success')): ?>
                    <div class="alert alert-secondary"><?= Yii::$app->session->getFlash('success') ?></div>
                <?php elseif (Yii::$app->session->hasFlash('danger')): ?>
                    <div class="alert alert-danger"><?= Yii::$app->session->getFlash('danger') ?></div>
                <?php endif; ?>
            </div>            
            <?php else: ?>
                <div class="col-12 bg-dark rounded shadow">
                <p class="fa-3x text-white font-weight-bolder border-bottom text-center">На сегодня заданий нет</p>
            </div>    
            <?php endif; ?>
        </div>
    </div>
</div>


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
   
    const gallery = new Viewer(document.getElementById('images'), {
        url: 'data-original'
    });
    
JS);