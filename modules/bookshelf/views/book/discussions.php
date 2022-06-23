<?php

use app\widgets\CommentWidget;
use yii\bootstrap4\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookShelf[] $discussions */

?>


<?php foreach($discussions as $discussion): ?>
    <div class="card card-item">
        <div class="card-header">
            <div class="row">
                <div class="col-1">
                    <?= Html::img($discussion->getPhoto(), ['style'=>'height: 8rem;', 'class'=>'img-thumbnail']) ?>
                </div>
                <div class="col">
                    <p class="lead border-bottom">
                        <?= $discussion->title ?>
                        <?= $discussion->writer ?>
                    </p>
                    <p class="badge badge-secondary fa-1x">
                        Комментариев → <?= count($discussion->comments) ?>
                    </p>
                    <p>
                        <?= Html::button('<i class="fas fa-arrow-alt-circle-down"></i> Показать', ['class' => 'btn-collapse btn btn-secondary btn-sm']) ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="card-body" style="display: none;">
            <?= CommentWidget::widget([
                'modelName' => 'bookshelf',
                'modelId' => $discussion->id,  
                'url' => Url::to(['/bookshelf/book/view', 'id'=>$discussion->id]),
                'title' => 'Дискуссия',
            ]) ?>
        </div>                   

    </div>           
<?php endforeach; ?>
    
<?php $this->registerJs(<<<JS
    $('.btn-collapse').off('click');
    $('.btn-collapse').on('click', function() {
        const btn = $(this);
        const div = $(this).parents('div.card-item').children('div.card-body');

        if (div.is(":hidden")) {
            btn.html('<i class="fas fa-arrow-alt-circle-up"></i> Скрыть');
        }
        else {
            btn.html('<i class="fas fa-arrow-alt-circle-down"></i> Показать');
        }

        div.slideToggle();        
    });
JS); ?>