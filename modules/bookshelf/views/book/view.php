<?php

use app\widgets\CommentWidget;
use kartik\rating\StarRating;
use yii\bootstrap4\Html;
use yii\widgets\Pjax;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookShelf $model */
/** @var app\modules\bookshelf\models\BookShelfRating $modelRating */

?>
<div class="book-shelf-view">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="col-3 border-right">
                        <?= Html::img($model->getPhoto(), ['class' => 'w-100']) ?>
                    </div>
                    <div class="col">                        
                        <div style="font-size: large;">
                            <strong>Описание:</strong> <?= $model->description ?>
                        </div>
                        <hr />
                        <?php Pjax::begin(['id' => 'pjax-bookshelf-rating', 'enablePushState' => false]) ?>
                        <?php $form = ActiveForm::begin([
                            'options' => [
                                'class' => 'mv-form',
                                'id' => 'form-rating',
                            ],
                        ]); ?>
                        <strong>Ваша оценка:</strong>
                        <?= StarRating::widget([
                            'model' => $modelRating,
                            'attribute' => 'rating',
                            'options' => [
                                'id' => 'rating-input',
                            ],
                            'pluginOptions' => [
                                'size' => 'sm',
                            ],
                        ]) ?>
                        <?php 
                            $this->registerJs(<<<JS
                                $('#rating-input').on('change', function() {
                                    $('#form-rating').submit();
                                });
                            JS);
                        ?>
                        <?php ActiveForm::end() ?>
                        <?php Pjax::end() ?>
                        <hr />
                        <strong>Дата получения:</strong> <?= Yii::$app->formatter->asDate($model->date_received) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <?= CommentWidget::widget([
                'modelName' => 'bookshelf',
                'modelId' => $model->id,
            ]) ?>
        </div>
    </div>
</div>
