<?php
/** @var yii\web\View $this */
/** @var app\models\vote\VoteNewyearToy[] $model */

use yii\bootstrap5\Html;
use yii\helpers\Url;
use app\models\vote\VoteNewyearToy;
use app\assets\fancybox\FancyboxAsset;
FancyboxAsset::register($this);

$isUnlimited = VoteNewyearToy::isUnlimited() ? 'true' : 'false';
$this->title = 'Голосование за лучшую новогоднюю игрушку в новогоднем конкурсе "Новогодний подарок на юбилей Службы"';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vote-newyear-toy-index">
    <h1><?= $this->title ?></h1>
    <hr />

    <?php if (VoteNewyearToy::showBtnVote()): ?>
    <div class="alert alert-info">
        <?php if (VoteNewyearToy::isVoted()): ?>
        Вы уже проголосовали!
        <?php else: ?>
        Вы можете проголосовать за 1 работу!
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="row">
        <?php foreach ($model as $item): ?>
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong><?= $item->name ?></strong>
                </div>
                <div class="panel-body">
                    <?php foreach ($item->files as $file): ?>
                        <a href="<?= $file->file_name ?>" data-fancybox="<?= $item->id ?>" data-caption="<?= $item->name ?>">
                            <img src="<?= $file->file_name ?>" class="thumbnail" style="height: 300px; float: left;" />
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="panel-footer">
                    <!--div class="caption">
                        <?= $item->description ?>
                    </div>
                    <hr /-->
                    <div class="btn-group">
                        <?php if (!VoteNewyearToy::isVoted() && VoteNewyearToy::showBtnVote()): ?>
                            <?= Html::button('Голосовать', ['class' => 'btn btn-primary btn-vote', 'data-href' => Url::to(['vote', 'id'=>$item->id])]) ?>
                        <?php endif; ?>

                        <?php if (VoteNewyearToy::showBtnStatistic()): ?>
                            <?= Html::a('Статистика <span class="label label-default">' . $item->countVote() . '</span>', ['statistic', 'id'=>$item->id], ['class' => 'btn btn-default mv-link']) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>
<?php $this->registerJs(<<<JS
    
    $('.btn-vote').on('click', function() {
        var btn = $(this);
        btn.prop('disabled', true);
        $.get(btn.data('href'))
            .done(function(data) {
                btn.parent('div').html('<div class="alert alert-success">Спасибо! Ваш голос учтен.</div>');
                if (!$isUnlimited) {
                    $('.btn-vote').hide();
                }
            })
            .fail(function(err) {
                console.log(err);
                btn.parent('div').html('<div class="alert alert-danger">' + err.responseText + '</div>');  
            })
            .always(function() {
                btn.prop('disabled', false);
            });         
        return false;
    });
    
JS
);
?>
