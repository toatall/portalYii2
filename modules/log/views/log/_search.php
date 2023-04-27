<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\log\models\LogSearch $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="log-search my-2 card">
    
    <div class="card-header fw-bold"><i class="fas fa-search"></i> Поиск</div>

    <div class="card-body">
        <?php $form = ActiveForm::begin([
            'layout'=> ActiveForm::LAYOUT_FLOATING,
            'action' => ['index'],
            'method' => 'get',
            'options' => [
                'data-pjax' => 1
            ],
        ]); ?>
    
        <?php echo $form->field($model, 'message') ?>

        <div class="btn-group mt-2">
            <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary btn-sm']) ?>
            <?= Html::resetButton('Очистить', ['class' => 'btn btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
