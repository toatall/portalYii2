<?php

use kartik\rating\StarRating;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\rookie\modules\tiktok\models\TiktokVote $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tiktok-vote-form card card-header">

    <?php $form = ActiveForm::begin([
        'options' => [           
            'data-pjax' => true,
            'id' => 'form-lifehack-like',
        ],
    ]); ?>

    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'rate')->widget(StarRating::class, [
        'pluginOptions'=>['step'=>1],        
    ])->label(false) ?>

    <?php ActiveForm::end(); ?>
    
</div>

<?php 
$idRateLifehack = Html::getInputId($model, 'rate');
$this->registerJs(<<<JS
    $('#$idRateLifehack').on('change', function() {
        $(this).parents('form').submit();
    });
JS); ?>