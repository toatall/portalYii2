<?php
/* @var $this \yii\web\View */
/* @var $model \app\models\vote\VoteMain */

use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\fancybox\FancyboxAsset;
FancyboxAsset::register($this);

$this->title = $model->name;
?>

<div class="index-vote">
    <h3><?= $this->title ?></h3>
    <hr />

    <?php if ($model->getEndVote()): ?>
    <div class="alert alert-info">Голосование заврешено</div>
    <?php else: ?>
        <div class="alert alert-info">
            Голосование проводится с <strong><?= \Yii::$app->formatter->asDate($model->date_start) ?></strong>
            по <strong><?= \Yii::$app->formatter->asDate($model->date_end) ?></strong>
        </div>
    <?php endif; ?>

    <?php if (strlen($model->description) > 0): ?>
        <div class="well well-small"><?= $model->description ?></div>
    <?php endif; ?>
    
    <?php foreach ($model->voteQuestions as $question): ?>
    <div class="panel panel-info">
        <div class="panel-heading"><?= $question->text_question ?></div>
        <div class="panel-body"><?= $question->text_html ?></div>
        <div class="panel-footer">
            <?php if (!$model->getEndVote() && !$model->isCountVoteEnd()): ?>
            <?= Html::button($question->isVoted() ? 'Вы уже проголосовали' : 'Голосовать', [
                    'class'=>'btn btn-primary link-voted',
                    'data-href'=> Url::to(['/vote/voted', 'id'=>$question->id]),
                    ($question->isVoted() ? 'disabled' : '') => '',
                ]
            ) ?>
            <?php /*else: ?>
                Голосов: <h1><span class="label label-default"><?= $question->count_votes ?></span></h1>
            <?php*/ endif; ?>
        </div>
    </div>
    <?php endforeach; ?>

</div>

<?php
$this->registerJs(<<<JS
    $.portalVoteHelper.init();
    $('.index-vote a').fancybox();
JS
);
?>