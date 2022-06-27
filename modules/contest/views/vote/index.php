<?php

/** @var yii\web\View $this */

use app\assets\LightGalleryAsset;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var app\modules\contest\models\VoteData[] $data */
/** @var app\modules\contest\models\Votemain $modelVoteMain */

$this->title = $modelVoteMain->title;

LightGalleryAsset::register($this);


$isDateVote = $modelVoteMain->isDateVote();
$isAuthorizeVote = $modelVoteMain->isAuthorizeVote();
?>

<p class="display-4 border-bottom">
    <?= $this->title ?>
</p>

<?php Pjax::begin(['id'=>'pjax-contest-vote-index', 'timeout'=>false, 'enablePushState'=>false]) ?>

<?php if ($data): ?>
    <?php 
    foreach($data as $nomination => $items): ?>
        <div class="card mb-3">
            <div class="card-header">
                <h3><?= $nomination ?></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach($items as $item): 
                        /** @var app\modules\contest\models\VoteData $item */
                        ?>
                        <div class="col-3 d-flex align-items-stretch mb-3">
                            <div class="card d-flex align-items-stretch" style="background-color: rgba(0, 0, 0, 0.03);">
                                <?php if ($item->file_type == 'image'): ?>
                                <a href="<?= $item->file ?>" class="gallery">
                                    <img src="<?= $item->file ?>" class="card-img" />
                                </a>
                                <?php endif; ?>
                                <div class="card-body lead text-center">
                                    <?= $item->title ?>
                                </div>
                                <?php if ($isAuthorizeVote): ?>
                                <div class="card-footer">
                                    <?php if ($isDateVote): ?>
                                        <?php if ($item->isVoted()): ?>
                                            <?= Html::button('<i class="fas fa-heart fa-1x"></i> Понравилось', 
                                                [
                                                    'class' => 'btn btn-primary btn-save', 
                                                    'data-pjax' => false,
                                                    'data-url' => Url::to(['/contest/vote/save-answer', 'id'=>$item->id, 'idMain'=>$item->id_contest_main]),
                                                ]) ?>
                                        <?php elseif ($item->isVotedNomination()): ?>
                                            Вы уже проголосовали за текущую номинацию
                                        <?php else: ?>
                                            <?= Html::button('<i class="far fa-heart fa-1x"></i> Голосовать',                                                  
                                                [
                                                    'class' => 'btn btn-outline-primary btn-save', 
                                                    'data-pjax' => false,
                                                    'data-url' => Url::to(['/contest/vote/save-answer', 'id'=>$item->id, 'idMain'=>$item->id_contest_main]),
                                                ]) ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        Голосование завершено
                                    <?php endif; ?>
                                </div>                                
                                <?php endif; ?>
                                <div class="card-footer">
                                    Количество голосов: <?= $item->getCountAnswers() ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-warning">Нет данных</div>
<?php endif; ?>

<?php $this->registerJs(<<<JS
    
    $('.btn-save').on('click', function() {
        const url = $(this).data('url');
        $(this).prop('disabled', true);
        $(this).append(' <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>');
        $.get(url)
        .done(function() {
            $.pjax.reload({ container: '#pjax-contest-vote-index', async: true });
        })
        .fail(function(err) {
            bs4Toast.error('Ошибка', err.responseText);            
        });

        return false;
    });

    $('.gallery').each(function() {
        lightGallery($(this).get(0), {
            addClass: 'lg-custom-thumbnails',  
            appendThumbnailsTo: '.lg-outer',
            animateThumb: false,
            allowMediaOverlap: true,
        });
    });

JS); ?>

<?php Pjax::end() ?>


