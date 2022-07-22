<?php

use app\widgets\CommentWidget;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\modules\rookie\modules\tiktok\models\Tiktok $model */
/** @var app\modules\rookie\modules\tiktok\models\TiktokVote $modelVote */

?>
<div class="tiktok-view">
    <p><?= $model->description ?></p>
    <hr />
    
    <div class="embed-responsive embed-responsive-21by9">
        <video class="embed-responsive-item" controls="">
            <source src="<?= $model->filename ?>">
        </video>
    </div>
</div>


    <div class="mt-4 card">
        <div class="card-header">
            Голосование
        </div>
        <div class="card-body">
            <?php Pjax::begin(['timeout'=>false, 'enablePushState'=>false]) ?>
            <?php if ($model->canVote()): ?>
                <?= $this->render('vote/_form', ['model' => $modelVote]) ?>                
            <?php else: ?>
                <?= $this->render('vote/_view', ['model' => $model]) ?>
            <?php endif; ?>
            <?php Pjax::end() ?>
        </div>
    </div>


<div class="mt-4">
    <?= CommentWidget::widget([
        'modelName' => 'rookie.tiktiok',
        'modelId' => $model->id,
    ]) ?>
</div>
