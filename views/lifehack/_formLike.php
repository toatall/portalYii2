<?php

use kartik\rating\StarRating;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\lifehack\LifehackLike $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="lifehack-life-form card card-body">

    <?php $form = ActiveForm::begin([
        'options' => [           
            'data-pjax' => true,
            'id' => 'form-lifehack-like',
        ],
    ]); ?>

    <?= $form->errorSummary($model) ?>

    <?= ''/* $form->field($model, 'rate')->widget(StarRating::class, [
        'pluginOptions'=>['step'=>1],        
    ])->label(false)*/ ?>

    
    <?php if ($model->rate): ?>
        <?= Html::submitButton('<i class="far fa-thumbs-up"></i> Мне нравится', ['class' => 'btn btn-primary']) ?>
        <?= Html::activeHiddenInput($model, 'rate', ['value' => 0]) ?>
    <?php else: ?>
        <?= Html::submitButton('<i class="far fa-thumbs-up"></i> Мне нравится', ['class' => 'btn btn-light']) ?>
        <?= Html::activeHiddenInput($model, 'rate', ['value' => 5]) ?>
    <?php endif ?>

    <?php ActiveForm::end(); ?>
    
</div>

<?php 
/*
$idRateLifehack = Html::getInputId($model, 'rate');
$this->registerJs(<<<JS
    $('#$idRateLifehack').on('change', function() {
        $(this).parents('form').submit();
    });
JS);*/ ?>