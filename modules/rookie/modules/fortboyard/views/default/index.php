<?php

/** @var yii\web\View $this */
/** @var app\modules\rookie\modules\fortboyard\models\FortBoyard $questionToday */
/** @var array $resultQuestions */

use app\helpers\DateHelper;
use app\modules\rookie\modules\fortboyard\models\FortBoyard;
use yii\helpers\Url;
use app\modules\rookie\modules\photohunter\assets\ViewerjsAsset;
use yii\bootstrap4\Html;
use yii\widgets\Pjax;

ViewerjsAsset::register($this);

$this->title = 'Проект "Форт Боярд"';
?>

<section class="jumbotron text-center bg-light" style="margin-top: -100px; background-color: #22140b !important; padding-bottom: 0;">
    <img src="/public/content/rookie/fortboyard/kisspng-fort-boyard-television-show-france-tv-game-show-fr-5b3eb1905dace6.6035552615308353443837.png" style="height: 20rem;" />
</section>

<div class="album bg-light" style="background-color: #22140b !important;">
    <div class="container">
        <div class="row">
            <?php if ($questionToday != null) : ?>
                <div class="col-12 bg-dark rounded shadow text-white pb-4">
                    <p class="fa-3x font-weight-bolder border-bottom text-center">Задание на сегодня</p>
                    <h4><?= $questionToday->title ?></h4>
                    <p><?= $questionToday->text ?></p>
                    <?php if ($questionToday->isRight()) : ?>
                        <hr class="bg-white" />
                        <?= Html::beginForm(Url::to(['/rookie/fortboyard/default/save-answer', 'id' => $questionToday->id])) ?>
                        <div class="row">
                            <div class="col-2">
                                <?= Html::label('Ваш ответ', 'input-answer') ?>
                            </div>
                            <div class="col">
                                <?= Html::textInput('answer', '', ['class' => 'form-control', 'id' => 'input-answer', 'required' => true]) ?>
                            </div>
                            <div class="col-2">
                                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                            </div>
                        </div>
                        <?= Html::endForm() ?>
                    <?php elseif (Yii::$app->session->hasFlash('success')) : ?>
                        <div class="alert alert-secondary"><?= Yii::$app->session->getFlash('success') ?></div>
                    <?php elseif (Yii::$app->session->hasFlash('danger')) : ?>
                        <div class="alert alert-danger"><?= Yii::$app->session->getFlash('danger') ?></div>
                    <?php endif; ?>
                </div>           
            <?php else : ?>
                <div class="col-12 bg-dark rounded shadow">
                    <!-- <p class="fa-3x text-white font-weight-bolder border-bottom text-center">На сегодня заданий нет</p> -->
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php 

Pjax::begin(['id' => 'pjax-fort-boyard-teams', 'timeout'=>false, 'enablePushState' => false]);

if ($resultQuestions) : ?>
    <div class="album bg-light mt-4" style="background-color: #22140b !important;">
        <div class="container">
            <div class="row">
                <div class="col-12 bg-dark rounded shadow text-white pb-4">
                    <p class="fa-3x text-white font-weight-bolder border-bottom text-center">Голосование</p>
                    <table class="table table-bordered text-white">
                        <?php foreach ($resultQuestions as $item) : ?>
                            <tr>
                                <td><?= $item['name'] ?></td>
                                <td>
                                    <?= ''//str_repeat('<i class="fas fa-scroll text-warning"></i>', $item['count_rights']) ?>
                                    За лучшее испытание 
                                    <span class="badge badge-counter badge-light fa-1x">                                        
                                        <?= Yii::$app->formatter->asDecimal($item['avg_trial'], 2) ?>
                                        <i class="text-warning fas fa-star"></i>
                                    </span><br />
                                    
                                    За оригинальное название и девиз команды 
                                    <span class="badge badge-counter badge-light fa-1x">
                                        <?= Yii::$app->formatter->asDecimal($item['avg_name'], 2) ?>
                                        <i class="text-warning fas fa-star"></i>
                                    </span>
                                 </td>
                                <?php if (FortBoyard::canVoid($item['id'])): ?>
                                <td style="width: 10rem;">
                                    <?= Html::a('<i class="fas fa-star"></i> Голосовать', ['/rookie/fortboyard/default/vote', 'idTeam'=>$item['id']], [
                                        'class' => 'btn btn-sm btn-primary link-modal',
                                        'data' => [
                                            'description' => 'Голосование',
                                            'title' => "за команду \"{$item['name']}\"",
                                            'pjax' => 0,
                                        ],
                                    ]) ?>
                                </td>
                                <?php else: ?>
                                <td>&nbsp;</td>
                                <?php endif; ?>
                                <td>
                                    <?= Html::a('<i class="fas fa-info-circle"></i>', ['/rookie/fortboyard/default/info', 'idTeam'=>$item['id']], [
                                        'class' => 'btn btn-info link-modal',
                                        'title' => 'Информация о команде',
                                        'data' => [
                                            'description' => 'Результаты ответов',
                                            'title' => "команды \"{$item['name']}\"",
                                            'pjax' => 0,
                                        ],
                                    ]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


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
   
    // const gallery = new Viewer(document.getElementById('images'), {
    //     url: 'data-original'
    // });
    
JS);

Pjax::end(); ?>