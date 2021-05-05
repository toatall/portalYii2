<?php
use yii\helpers\Html;
use app\modules\events\models\ContestArtsVote;
use kartik\rating\StarRating;

/* @var $this yii\web\View */
/* @var $model app\modules\events\models\ContestArts */


$voteRealArt = ContestArtsVote::isVoted($model->id, ContestArtsVote::TYPE_VOTE_REAL_ART);
$voteOriginalName = ContestArtsVote::isVoted($model->id, ContestArtsVote::TYPE_VOTE_ORIGINAL_NAME);

?>

<div class="col-6" style="margin-bottom: 20px;">
    <div class="card" style="background-image: url('/img/24.png');">
        <div class="card-header">
            <?= $model->image_original_title ?><br />
            <?= $model->image_original_author ?>
        </div>
        <div class="card-body">
            <?= Html::a(Html::img($model->image_reproduced, ['class' => 'img-thumbnail border-art-small', 'style'=>'margin: 0 auto; max-height:10em; max-width:100%;',]), 
                $model->image_reproduced, ['class' => 'fancybox']) ?>
            <?= Html::a(Html::img($model->image_original, ['class' => 'img-thumbnail border-art-small', 'style'=>'margin: 0 auto; max-height:10em; max-width:100%;',]), 
                $model->image_original, ['class' => 'fancybox']) ?>
        </div>
       
        <div class="card-footer">
            <?='' /* StarRating::widget([
                'name' => 'rating_vote_real_art',
                'value' => 3.56,
                'pluginOptions' => [
                    'step' => 1,                    
                ],
            ])*/ ?>
        </div>
    </div>
</div>
<?php $this->registerJs(<<<JS
    $('.link-vote').on('click', function() {
        var i = $(this).children('i');
        var a = $(this);
        i.attr('class', 'fas fa-circle-notch fa-spin');
        $.get($(this).attr('href'))
        .done(function(data) {
            if (data == 'OK') {
                if (i.hasClass('far')) {
                    i.attr('class', 'fas fa-heart');
                    a.removeClass('btn-light');
                    a.addClass('btn-secondary');
                }
                else {
                    i.attr('class', 'far fa-heart');
                    a.removeClass('btn-secondary');
                    a.addClass('btn-light');
                }
            }            
        })
        .fail(function(jqXHR) {
            a.html('<div class="alert alert-danger">' + jqXHR.responseText + '</div>');
        });
        return false;
    });   
JS
); ?>