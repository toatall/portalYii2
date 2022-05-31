<?php 

/** @var yii\web\View $this */

use app\widgets\CommentWidget;

/** @var stdClass $model */

?>

<div class="row">
    <div class="col-12">
        <?php foreach($model->videos as $video): ?>
        <div class="card">
            <div class="card-body">
                <div class="embed-responsive embed-responsive-21by9">
                    <video class="embed-responsive-item" controls="">
                        <source src="<?= $video ?>">
                    </video>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>



<div class="mt-4">
    <?= CommentWidget::widget([
        'modelName' => 'UnderstandMe',
        'modelId' => 0,
    ]) ?>
</div>