<?php

use kartik\rating\StarRating;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\rookie\modules\tiktok\models\TiktokVote $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tiktok-vote-form">

    <?php $form = ActiveForm::begin([
        //'action' => ['/rookie/tiktok/default/vote', 'id'=>$model->id],
        'options' => [
            // 'id' => 'form-rookie-tiktok-vote',
            'data-pjax' => true,
        ],
    ]); ?>

    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'rate_1')->widget(StarRating::class, [
        'pluginOptions'=>['step'=>1],        
    ]) ?>

    <?= $form->field($model, 'rate_2')->widget(StarRating::class, [
        'pluginOptions'=>['step'=>1],        
    ]) ?>

    <?= $form->field($model, 'rate_3')->widget(StarRating::class, [
        'pluginOptions'=>['step'=>1],        
    ]) ?>

    <hr />
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    
</div>
